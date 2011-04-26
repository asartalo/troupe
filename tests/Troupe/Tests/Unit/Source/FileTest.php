<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\File;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;


class FileTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->url = 'http://source.com/afile.phar';
    $this->data_dir = 'a/path/to/a/directory';
    $this->cibo = $this->quickMock('Cibo');
    $this->vdm = $this->quickMock(
      'Troupe\VendorDirectory\Manager',
      array('isDataImported', 'importSuccess')
    );
    $this->file_source = new File(
      $this->url, $this->vdm, $this->utilities, $this->data_dir, $this->cibo
    );
  }
  
  function testImportChecksWithVdmIfDataHasAlreadyBeenImported() {
    $this->vdmExpectsIsDataImported()->with($this->url);
    $this->file_source->import();
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
      ->with($this->url, $this->data_dir . '/afile.phar');
    $this->file_source->import();
  }
  
  function testImportSkipsDownloadIfFileHasAlreadyBeenImported() {
    $this->vdmIsDataImported(true);
    $this->cibo->expects($this->never())
      ->method('download');
    $this->file_source->import();
  }
  
  function testImportReturnsOkayStatusMessageIfVdmSaysDataHasAlreadyBeenImported() {
    $folder_name = md5($this->url);
    $this->vdmIsDataImported(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: {$this->url} has already been imported.",
      "{$this->data_dir}/$folder_name"
    );
    $this->assertEquals($status, $this->file_source->import());
  }
  
  function testImportMarksUrlAsImportedOnVdmWhenDownloadIsSuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $this->vdm->expects($this->once())
      ->method('importSuccess')
      ->with($this->url);
    $this->file_source->import();
  }
  
  function testImportDoesNotMarkUrlAsImportedOnVdmWhenDownloadIsUnsuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(false);
    $this->vdm->expects($this->never())
      ->method('importSuccess');
    $this->file_source->import();
  }
  
  function testImporReturnsSuccessStatusWhenSuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: Imported {$this->url}.",
      $this->data_dir . '/' . md5($this->url)
    );
    $this->assertEquals($status, $this->file_source->import());
  }
  
  function testImporReturnsFailureStatusWhenDownloadIsUnsuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(false);
    $status = new Failure(
      \Troupe\Source\STATUS_FAIL,
      "FAIL: Unable to import {$this->url}. There was a problem downloading " .
      "the remote resource."
    );
    $this->assertEquals($status, $this->file_source->import());
  }
  
  function testImportRenamesDownloadedFileToTheExpectedPathIfSuccessful() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $this->utilities->expects($this->once())
      ->method('rename')
      ->with(
        $this->data_dir . '/afile.phar',
        $this->data_dir . '/' . md5($this->url)
      );
    $this->file_source->import();
  }
    
  function testUpdateDoesNotDownloadFileWhenItHasBeenImportedAlready() {
    $this->vdmIsDataImported(true);
    $this->cibo->expects($this->never())
      ->method('download');
    $this->file_source->update();
  }
  
  function testUpdateCallsImportWhenItHasntBeenDownloadedYet() {
    $this->vdmIsDataImported(false);
    $this->ciboDownload(true);
    $status = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: Imported {$this->url}.",
      $this->data_dir . '/' . md5($this->url)
    );
    $this->assertEquals($status, $this->file_source->update());
  }
  
}
