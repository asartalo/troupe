<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Importer;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class ImporterTest extends \Troupe\Tests\TestCase {
  
  public function setUp() {
    $this->project_dir = 'foo/path';
    $this->dependency = $this->quickMock('Troupe\Dependency\Dependency');
    $this->utilities = $this->quickMock(
      'Troupe\SystemUtilities',
      array('symlink', 'readlink', 'fileExists', 'out', 'unlink')
    );
    $this->importer = new Importer($this->utilities);
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
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($dep_path, $local_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportSkipsSymlinkWhenStatusSuccessCheckReturnsFalse() {
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->statusIsSuccessful(false);
    $this->utilities->expects($this->never())
      ->method('symlink');
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
  
  function testImportChecksIfLocalLocationExists() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->statusIsSuccessful(); // TODO: There should be no need for this...
    $this->dependencyReturnsLocalLocation($local_path);
    $this->utilities->expects($this->atLeastOnce())
      ->method('fileExists')
      ->with($local_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportSkipsLinkCheckIfLocalLocationOrLinkDoesNotExist() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->statusIsSuccessful(); // TODO: There should be no need for this...
    $this->dependencyReturnsLocalLocation($local_path);
    $this->utilities->expects($this->atLeastOnce())
      ->method('fileExists')
      ->will($this->returnValue(false));
    $this->utilities->expects($this->never())
      ->method('readlink');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportChecksSymlinkPointsToDependencyPath() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->dependencyReturnsDataLocation($dep_path);
    $this->statusIsSuccessful();
    $this->dependencyReturnsLocalLocation($local_path);
    $this->utilities->expects($this->atLeastOnce())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->with($local_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportSkipsSymlinkWhenSymlinkPointsToDependencyPath() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->dependencyReturnsDataLocation($dep_path);
    $this->statusIsSuccessful();
    $this->dependencyReturnsLocalLocation($local_path);
    $this->utilities->expects($this->atLeastOnce())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->will($this->returnValue($dep_path));
    $this->utilities->expects($this->never())
      ->method('symlink');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportRemovesIncorrectSymlinkBeforeCorrectingIt() {
    $dep_path = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependencyReturnsStatus();
    $this->dependencyReturnsDataLocation($dep_path);
    $this->statusIsSuccessful();
    $this->dependencyReturnsLocalLocation($local_path);
    $this->utilities->expects($this->atLeastOnce())
      ->method('fileExists')
      ->with($local_path)
      ->will($this->returnValue(true));
    $this->utilities->expects($this->once())
      ->method('readlink')
      ->will($this->returnValue('bar'));
    $this->utilities->expects($this->once())
      ->method('unlink')
      ->with($local_path);
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($dep_path, $local_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
}
