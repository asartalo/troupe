<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Archive;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class ArchiveTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->expander  = $this->quickMock('Troupe\Expander\Zip');
    $this->utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->url = 'http://zip.source.com/example.zip';
    $this->data_dir = 'a/path/to/a/directory';
    $this->cibo = $this->quickMock('Cibo');
    $this->vdm = $this->quickMock(
      'Troupe\VendorDirectory\Manager',
      array('isDataImported', 'importSuccess')
    );
    $this->zip_source = new Archive(
      $this->url, $this->vdm, $this->utilities, $this->data_dir,
      $this->expander, $this->cibo
    );
  }
  
  function testImportChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdmExpectsIsDataImported()->with($this->url);
    $this->zip_source->import();
  }
  
  private function vdmIsDataImported($bool) {
    return $this->vdmExpectsIsDataImported()
      ->will($this->returnValue($bool));
  }
  
  private function vdmExpectsIsDataImported() {
    return $this->vdm->expects($this->once())
      ->method('isDataImported');
  }
  
  private function ciboDownload($bool) {
    return $this->cibo->expects($this->once())
      ->method('download')
      ->will($this->returnValue($bool));
  }

  function testImportDownloadsFile() {
    $this->vdmIsDataImported(false);
    $this->cibo->expects($this->once())
      ->method('download')
      ->with($this->url, $this->data_dir . '/example.zip');
    $this->zip_source->import();
  }
  
  function testImportSkipsDownloadIfFileHasAlreadyBeenImported() {
    $this->vdmIsDataImported(true);
    $this->cibo->expects($this->never())
      ->method('download');
    $this->zip_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfVdmSaysDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: {$this->url} has already been imported.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImportMarksUrlAsImportedOnVdmWhenDownloadIsSuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $this->vdm->expects($this->once())
      ->method('importSuccess')
      ->with($this->url);
    $this->zip_source->import();
  }
  
  function testImportDoesNotMarkUrlAsImportedOnVdmWhenDownloadIsUnsuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(false);
    $this->vdm->expects($this->never())
      ->method('importSuccess');
    $this->zip_source->import();
  }
  
  function testImporReturnsSuccessStatusWhenSuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: Imported {$this->url}.",
      $this->data_dir . '/' . md5($this->url)
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImporReturnsFailureStatusWhenDownloadIsUnsuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(false);
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL,
      "FAIL: Unable to import {$this->url}. There was a problem downloading " .
      "the remote resource."
    );
    $this->assertEquals($status, $this->zip_source->import());
  }
  
  function testImportExpandsDownloadedFile() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $this->expander->expects($this->once())
      ->method('expand')
      ->with(
        $this->data_dir . '/example.zip',
        $this->data_dir . '/' . md5($this->url)
      );
    $this->zip_source->import();
  }
  
  function testImportDoesNotExpandFileWhenDownloadIsUnsuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(false);
    $this->expander->expects($this->never())
      ->method('expand');
    $this->zip_source->import();
  }
  
}
