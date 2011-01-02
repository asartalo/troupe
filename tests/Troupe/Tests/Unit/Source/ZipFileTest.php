<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\ZipFile;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class ZipFileTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->expander  = $this->quickMock('Troupe\Expander\Zip');
    $this->utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->url = 'http://zip.source.com/example.zip';
    $this->data_dir = 'a/path/to/a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager', array('isDataImported', 'importSuccess'));
    $this->zip_source = new ZipFile($this->url, $this->vdm, $this->utilities, $this->data_dir, $this->expander);
  }
  
  function testImportChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->with($this->url);
    $this->zip_source->import();
  }
  
  private function vdmIsDataImported($bool) {
    return $this->vdm->expects($this->once())
      ->method('isDataImported')
      ->will($this->returnValue($bool));
  }

  function testImportDownloadsFile() {
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->with($this->url, 'rb');
    $this->zip_source->import();
  }
  
  function testImportSkipsDownloadIfFileHasAlreadyBeenImported() {
    $this->vdmIsDataImported(true);
    $this->utilities->expects($this->never())
      ->method('fopen');
    $this->zip_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfVdmSaysDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK, "SUCCESS: {$this->url} has already been imported.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImportSavesFileInDataDirectoryFirst() {
    $remote_handle = "A dummy http resource handle.";
    $local_handle = "A local file (for the temporary zip file) handle.";
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $this->utilities->expects($this->at(1))
      ->method('fopen')
      ->with($this->data_dir . '/example.zip', 'wb')
      ->will($this->returnValue($local_handle));
    $this->utilities->expects($this->once())
      ->method('stream_get_contents')
      ->with($remote_handle)
      ->will($this->returnValue('foo'));
    $this->utilities->expects($this->once())
      ->method('fwrite')
      ->with($local_handle, 'foo');
    $this->zip_source->import();
  }
  
  function testImportMarksUrlAsImportedOnVdm() {
    $remote_handle = "A dummy http resource handle.";
    $local_handle = "A local file (for the temporary zip file) handle.";
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $this->utilities->expects($this->at(1))
      ->method('fopen')
      ->will($this->returnValue($local_handle));
    $this->utilities->expects($this->once())
      ->method('stream_get_contents')
      ->will($this->returnValue('foo'));
    $this->utilities->expects($this->once())
      ->method('fwrite')
      ->with($local_handle, 'foo');
    $this->vdm->expects($this->once())
      ->method('importSuccess')
      ->with($this->url);
    $this->zip_source->import();
  }
  
  function testImporReturnsSuccessStatusWhenSuccessful() {
    $remote_handle = "A dummy http resource handle.";
    $local_handle = "A local file (for the temporary zip file) handle.";
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $this->utilities->expects($this->at(1))
      ->method('fopen')
      ->will($this->returnValue($local_handle));
    $this->utilities->expects($this->once())
      ->method('stream_get_contents')
      ->will($this->returnValue('foo'));
    $this->utilities->expects($this->once())
      ->method('fwrite')
      ->with($local_handle, 'foo');
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: Imported {$this->url}.",
      $this->data_dir . '/' . md5($this->url)
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImportChecksConnectionToRemoteFileFirstBeforeExpanding() {
    $remote_handle = false;
    $local_handle = "A local file (for the temporary zip file) handle.";
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $this->utilities->expects($this->never())
      ->method('stream_get_contents');
    $this->utilities->expects($this->never())
      ->method('fwrite');
    $this->zip_source->import();
  }
  
  function testImportReturnsFailureStatusMessageWhenConnectionFails() {
    $remote_handle = false;
    $local_handle = "A local file (for the temporary zip file) handle.";
    $this->vdmIsDataImported(false);
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL,
      "FAIL: Unable to import {$this->url}. There was a problem connecting to the remote resource."
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImportExpandsSavedZipFile() {
    $remote_handle = "A dummy http resource handle.";
    $this->utilities->expects($this->at(0))
      ->method('fopen')
      ->will($this->returnValue($remote_handle));
    $this->vdmIsDataImported(false);
    $saved_zip_file = $this->data_dir . '/example.zip';
    $this->expander->expects($this->once())
      ->method('expand')
      ->with($saved_zip_file, $this->data_dir . '/' . md5($this->url));
    $this->zip_source->import();
  }
  
}
