<?php
namespace Troupe\Tests\Unit\VendorDirectory;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\VendorDirectory\Manager as VendorDirectoryManager;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Troupe\Settings;

class ManagerTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->utilities = $this->quickMock(
      'Troupe\SystemUtilities',
      array('symlink', 'readlink', 'fileExists', 'unlink', 'mkdir', 'umask')
    );
    $this->project_link = 'a/link/path';
    $this->source_path = 'the/original/vendor/path';
    $this->settings = new Settings(array('vendor_dir' => 'foo/bar/vendor'));
    $this->data_store = $this->quickMock('Troupe\DataStore', array('set', 'get'));
    $this->VDM = new VendorDirectoryManager($this->utilities, $this->data_store, $this->settings);
  }
  
  private function fileExistsCalled($times = null) {
    $times = $times ? $times : $this->once();
    return $this->utilities->expects($times)
      ->method('fileExists');
  }
  
  private function fileExistsWillReturn($bool = true, $times = null) {
    $times = $times ? $times : $this->once();
    return $this->fileExistsCalled($times)->will($this->returnValue($bool));
  }
  
  function testLinkingChecksIfLinkExistsFirst() {
    $this->fileExistsCalled()
      ->with($this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesWhenLinkFileDoesNotExist() {
    $this->fileExistsCalled()
      ->with($this->project_link)
      ->will($this->returnValue(false));
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testSkipLinkingWhenLinkAlreadyPointsToSourcePath() {
    $this->fileExistsWillReturn(true);
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue($this->source_path));
    $this->utilities->expects($this->never())
      ->method('symlink');
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testSkipReadLinkWhenFileDoesNotExist() {
    $this->fileExistsWillReturn(false);
    $this->utilities->expects($this->never())
      ->method('readlink');
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesWhenLinkDoesNotPointToSourcePath() {
    $this->fileExistsWillReturn(true);
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue('foobar'));
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testLinkDirectoriesDeletesLinkWhenLinkDoesNotPointToSourcePath() {
    $this->fileExistsWillReturn(true);
    $this->utilities->expects($this->at(1))
      ->method('readlink')
      ->with($this->project_link)
      ->will($this->returnValue('foobar'));
    $this->utilities->expects($this->at(2))
      ->method('unlink')
      ->with($this->project_link);
    $this->utilities->expects($this->at(3))
      ->method('symlink')
      ->with($this->source_path, $this->project_link);
    $this->VDM->link($this->project_link, $this->source_path);
  }
  
  function testGetVendorDirChecksIfVendorDirExists() {
    $this->fileExistsCalled()->with('foo/bar/vendor');
    $this->VDM->getVendorDir();
  }
  
  function testGetVendorDirReturnsVendorDirIfVendorDirExists() {
    $this->fileExistsCalled()->with('foo/bar/vendor')->will($this->returnValue(true));
    $this->assertEquals('foo/bar/vendor', $this->VDM->getVendorDir());
  }
  
  function testGetVendorDirCreatesVendorDirIfVendorDirDoesNotExist() {
    $this->fileExistsCalled()->with('foo/bar/vendor')->will($this->returnValue(false));
    $this->utilities->expects($this->once())
      ->method('mkdir')
      ->with('foo/bar/vendor', 0755, true);
    $this->assertEquals('foo/bar/vendor', $this->VDM->getVendorDir());
  }
  
  function testGetVendorDirSkipsCreatingVendorDirIfVendorDirExists() {
    $this->fileExistsCalled()->with('foo/bar/vendor')->will($this->returnValue(true));
    $this->utilities->expects($this->never())
      ->method('mkdir');
    $this->assertEquals('foo/bar/vendor', $this->VDM->getVendorDir());
  }
  
  function testImportSuccess() {
    $url = 'http://sample.library.url/';
    $this->data_store->expects($this->once())
      ->method('set')
      ->with('data_import', $url, true);
    $this->VDM->importSuccess($url);
  }
  
  function testIsDataImportedGetsValueFromDataStore() {
    $url = 'http://sample.library.url/';
    $this->data_store->expects($this->once())
      ->method('get')
      ->with('data_import', $url);
    $this->VDM->isDataImported($url);
  }
  
  function testIsDataImportedReturnsValueFromDataStore() {
    $url = 'http://sample.library.url/';
    $this->data_store->expects($this->once())
      ->method('get')
      ->with('data_import', $url)
      ->will($this->returnValue(true));
    $this->assertTrue($this->VDM->isDataImported($url));
  }
  
  function testIsDataImportedReturnsValueFromDataStore2() {
    $url = 'http://sample.library.url/';
    $this->data_store->expects($this->once())
      ->method('get')
      ->with('data_import', $url)
      ->will($this->returnValue(false));
    $this->assertFalse($this->VDM->isDataImported($url));
  }
  
  function testIsDataImportedReturnsFalseIfDataStoreReturnsNull() {
    $url = 'http://sample.library.url/';
    $this->data_store->expects($this->once())
      ->method('get')
      ->with('data_import', $url)
      ->will($this->returnValue(null));
    $this->assertFalse($this->VDM->isDataImported($url));
  }
  
}
