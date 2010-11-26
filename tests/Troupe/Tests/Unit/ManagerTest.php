<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    $this->dependency1 = $this->quickMock('Troupe\Dependency');
    $this->dependency2 = $this->quickMock('Troupe\Dependency');
    $this->dependencies = array(
      $this->dependency1, $this->dependency2
    );
    $this->importer = $this->quickMock('Troupe\Importer', array('import'));
    $this->projectRootDir = 'a/foo/path';
    $this->manager = new Manager($this->projectRootDir, $this->dependencies, $this->importer);
  }
  
  function testGetDependencies() {
    $this->assertEquals(
      $this->dependencies, $this->manager->getDependencies()
    );
  }
  
  private function quickMock($class, array $methods = array()) {
    return $this->getMock($class, $methods, array(), '', false);
  }
  
  function testManageDependenciesPassesDependenciesToImporter() {
    $this->importer->expects($this->at(0))
      ->method('import')
      ->with($this->projectRootDir, $this->dependency1);
    $this->importer->expects($this->at(1))
      ->method('import')
      ->with($this->projectRootDir, $this->dependency2);
    $this->manager->manageDependencies();
  }
  
}