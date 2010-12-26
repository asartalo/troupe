<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Manager;
use \Troupe\SystemUtilities;

class ManagerTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->dependency1 = $this->quickMock('Troupe\Dependency\Dependency');
    $this->dependency2 = $this->quickMock('Troupe\Dependency\Dependency');
    $this->dependencies = array(
      $this->dependency1, $this->dependency2
    );
    $this->projectRootDir = 'a/foo/path';
    $this->importer = $this->quickMock('Troupe\Importer', array('import'));
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager', array('getVendorDir'));
    $this->logger = $this->quickMock('Troupe\Logger', array('log'));
    $this->manager = new Manager(
      $this->projectRootDir, $this->dependencies, $this->importer,
      $this->system_utilities, $this->vdm, $this->logger
    );
  }
  
  function testGetDependencies() {
    $this->assertEquals(
      $this->dependencies, $this->manager->getDependencies()
    );
  }
  
  function testManageDependenciesAsksVdmForVendorDirectory() {
    $this->vdm->expects($this->exactly(2))
      ->method('getVendorDir');
    $this->manager->manageDependencies();
  }
  
  function testManageDependenciesPassesDependenciesToImporter() {
    $this->vdm->expects($this->any())
      ->method('getVendorDir')
      ->will($this->returnValue('foo/bar'));
    $this->importer->expects($this->at(0))
      ->method('import')
      ->with('foo/bar', $this->dependency1);
    $this->importer->expects($this->at(1))
      ->method('import')
      ->with('foo/bar', $this->dependency2);
    $this->manager->manageDependencies();
  }
  
  function testManageDependenciesSaysImportingMessage() {
    $this->dependency1->expects($this->any())
      ->method('getName')
      ->will($this->returnValue('Foo'));
    $this->dependency2->expects($this->any())
      ->method('getName')
      ->will($this->returnValue('Bar'));
    $this->system_utilities->expects($this->at(0))
      ->method('out')
      ->with("\n==========\nImporting: Foo");
    $this->system_utilities->expects($this->at(1))
      ->method('out')
      ->with("\n==========\nImporting: Bar");
    $this->manager->manageDependencies();
  }
  
  function testManageDependenciesPassesImportStatusToLogger() {
    $status = $this->quickMock('Troupe\Status\Status');
    $this->importer->expects($this->any())
      ->method('import')
      ->will($this->returnValue($status));
    $this->logger->expects($this->exactly(2))
      ->method('log')
      ->with('import_results', $status);
    $this->manager->manageDependencies();
  }
  
}
