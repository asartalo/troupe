<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Importer;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Troupe\Dependency\DependencyInterface;

class StubDependency implements \Troupe\Dependency\DependencyInterface {
  
  private $status,
    $data_location = 'a/path/to/the/dependency', 
    $local_location = 'lib/path/my_dependency';
  
  function __construct(\Troupe\Status\Status $status) {
    $this->status = $status;
  }
  
  function import() {
    return $this->status;
  }
  
  function update() {
    return $this->status;
  }
  
  function setLocalLocation($location) {
    $this->local_location = $location;
  }
  
  function getLocalLocation() {
    return $this->local_location;
  }
  
  function setDataLocation($location) {
    $this->data_location = $location;
  }
  
  function getDataLocation() {
    return $this->data_location;
  }
  
  function getUrl() {}
  
  function __toString() {}
  
  function getSource() {}
  
  function getName() {
    return 'Foo';
  }
  
}

class ImporterTest extends \Troupe\Tests\TestCase {
  
  public function setUp() {
    $this->project_dir = 'foo/path';
    $this->VDM = $this->quickMock('Troupe\VendorDirectory\Manager');
    $this->output = $this->quickMock('Troupe\Output',array('out'));
    $this->importer = new Importer($this->VDM, $this->output);
    $this->status = $this->quickMock('Troupe\Status\Status');
    $this->dependency = new StubDependency($this->status);
  }
  
  private function statusIsSuccessful($bool = true) {
    return $this->status->expects($this->once())
      ->method('isSuccessful')
      ->will($this->returnValue($bool));
  }
  
  function testImportCallsImportFromDependencyAndReturnsStatus() {
    $this->dependency = $this->quickMock('Troupe\Dependency\Dependency');
    $this->dependency->expects($this->once())
      ->method('import')
      ->will($this->returnValue($this->status));
    $this->assertEquals(
      $this->status, 
      $this->importer->import($this->project_dir, $this->dependency)
    );
  }
  
  function testImportLinksPathsWhenStatusIsSuccessful() {
    $this->statusIsSuccessful();
    $this->VDM->expects($this->once())
      ->method('link')
      ->with(
        $this->dependency->getLocalLocation(),
        $this->dependency->getDataLocation()
      );
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportSkipsLinkWhenStatusSuccessCheckReturnsFalse() {
    $this->statusIsSuccessful(false);
    $this->VDM->expects($this->never())
      ->method('link');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportReturnsStatus() {
    $this->assertEquals(
      $this->status,
      $this->importer->import($this->project_dir, $this->dependency)
    );
  }
  
  function testImportOutputsStatusMessage() {
    $this->status->expects($this->once())
      ->method('getMessage')
      ->will($this->returnValue('foo bar'));
    $this->output->expects($this->once())
      ->method('out')
      ->with('foo bar');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportGetsDependencyDataLocationWhenStatusImportIsSuccessful() {
    $this->statusIsSuccessful();
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testUpdateCallsUpdateFromDependencyAndReturnsStatus() {
    $this->dependency = $this->quickMock('Troupe\Dependency\Dependency');
    $this->dependency->expects($this->once())
      ->method('update')
      ->will($this->returnValue($this->status));
    $this->assertEquals(
      $this->status, 
      $this->importer->update($this->project_dir, $this->dependency)
    );
  }
  
  function testUpdateOutputsStatusMessage() {
    $this->status->expects($this->once())
      ->method('getMessage')
      ->will($this->returnValue('update message'));
    $this->output->expects($this->once())
      ->method('out')
      ->with('update message');
    $this->importer->update($this->project_dir, $this->dependency);
  }
  
}
