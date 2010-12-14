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
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->importer = $this->quickMock('Troupe\Importer', array('import'));
    $this->projectRootDir = 'a/foo/path';
    $this->manager = new Manager(
      $this->projectRootDir, $this->dependencies, $this->importer,
      $this->system_utilities
    );
  }
  
  function testGetDependencies() {
    $this->assertEquals(
      $this->dependencies, $this->manager->getDependencies()
    );
  }
  
  function testManageDependenciesPassesDependenciesToImporter() {
    $this->importer->expects($this->at(0))
      ->method('import')
      ->with($this->projectRootDir . '/vendor', $this->dependency1);
    $this->importer->expects($this->at(1))
      ->method('import')
      ->with($this->projectRootDir . '/vendor', $this->dependency2);
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
  
}