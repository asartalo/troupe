<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Importer;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class ImporterTest extends \Troupe\Tests\TestCase {
  
  public function setUp() {
    $this->project_dir = 'foo/path';
    $this->VDM = $this->quickMock('Troupe\VendorDirectoryManager');
    $this->dependency = $this->quickMock('Troupe\Dependency\Dependency');
    $this->utilities = $this->quickMock('Troupe\SystemUtilities',array('out'));
    $this->importer = new Importer($this->VDM, $this->utilities);
    $this->status = $this->quickMock('Troupe\Status\Status');
  }
  
  function testImportingDependency() {
    $this->dependencyReturnsStatus();
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  private function dependencyReturnsStatus() {
    return $this->dependency->expects($this->once())
      ->method('load')
      ->will($this->returnValue($this->status));
  }
  
  private function dependencyReturnsLocalLocation($local_path, $times = false) {
    $times = $times ? $times : $this->any();
    return $this->dependency->expects($this->once())
      ->method('getLocalLocation')
      ->will($this->returnValue($local_path));
  }
  
  private function statusIsSuccessful($bool = true) {
    return $this->status->expects($this->once())
      ->method('isSuccessful')
      ->will($this->returnValue($bool));
  }
  
  private function dependencyReturnsDataLocation($dep_path) {
    return $this->dependency->expects($this->once())
      ->method('getDataLocation')
      ->will($this->returnValue($dep_path));
  }
  
  function testImportRetrievesStatusFromLoad() {
    $dep_path   = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->dependencyReturnsDataLocation($dep_path);
    $this->statusIsSuccessful();
    $this->dependencyReturnsLocalLocation($local_path);
    $this->VDM->expects($this->once())
      ->method('link')
      ->with($local_path, $dep_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportSkipsLinkWhenStatusSuccessCheckReturnsFalse() {
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->statusIsSuccessful(false);
    $this->VDM->expects($this->never())
      ->method('link');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportReturnsStatus() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->assertEquals(
      $this->status,
      $this->importer->import($this->project_dir, $this->dependency)
    );
  }
  
  function testImportReturnsStatusEchoesMessage() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->status->expects($this->once())
      ->method('getMessage')
      ->will($this->returnValue('foo bar'));
    $this->utilities->expects($this->once())
      ->method('out')
      ->with('foo bar');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportGetsDependencyDataLocation() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->dependency->expects($this->once())
      ->method('getDataLocation');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
}
