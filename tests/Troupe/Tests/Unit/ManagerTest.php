<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Manager;
use \Troupe\SystemUtilities;
use \Troupe\Dependency\Dependency;

class StubVDM extends \Troupe\VendorDirectory\Manager {

  function __construct() {}
  
  function getVendorDir() {
    return 'foo/bar';
  }
  
}

class StubSource extends \Troupe\Source\AbstractSource {
  
  protected $url;  
  
  function __construct($url) {
    $this->url = $url;
  }
  
  function import() {
    
  }
}

class ManagerTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->dependency1 = $this->quickMock('Troupe\Dependency\Dependency');
    $this->dependency2 = $this->quickMock('Troupe\Dependency\Dependency');
    
    /*$this->dependency1 = new Dependency(
      'Foo', new StubSource('http://foo.com/foo')*/
    $this->dependencies = array(
      $this->dependency1, $this->dependency2
    );
    $this->projectRootDir = 'a/foo/path';
    $this->importer = $this->quickMock('Troupe\Importer', array('import'));
    $this->output = $this->quickMock('Troupe\Output', array('out'));
    $this->vdm = new StubVDM;
    $this->logger = $this->quickMock('Troupe\Logger', array('log'));
    $this->manager = new Manager(
      $this->projectRootDir, $this->dependencies, $this->importer,
      $this->output, $this->vdm, $this->logger
    );
  }
  
  function testGetDependencies() {
    $this->assertEquals(
      $this->dependencies, $this->manager->getDependencies()
    );
  }
  
  function testimportDependenciesPassesDependenciesToImporter() {
    $this->importer->expects($this->at(0))
      ->method('import')
      ->with('foo/bar', $this->dependency1);
    $this->importer->expects($this->at(1))
      ->method('import')
      ->with('foo/bar', $this->dependency2);
    $this->manager->importDependencies();
  }
  
  function testimportDependenciesSaysImportingMessage() {
    $this->dependency1->expects($this->any())
      ->method('getName')
      ->will($this->returnValue('Foo'));
    $this->dependency2->expects($this->any())
      ->method('getName')
      ->will($this->returnValue('Bar'));
    $this->output->expects($this->at(0))
      ->method('out')
      ->with("\n==========\nImporting: Foo");
    $this->output->expects($this->at(1))
      ->method('out')
      ->with("\n==========\nImporting: Bar");
    $this->manager->importDependencies();
  }
  
  function testimportDependenciesPassesImportStatusToLogger() {
    $status = $this->quickMock('Troupe\Status\Status');
    $this->importer->expects($this->any())
      ->method('import')
      ->will($this->returnValue($status));
    $this->logger->expects($this->exactly(2))
      ->method('log')
      ->with('import_results', $status);
    $this->manager->importDependencies();
  }
  
  function testGettingVendorDirectory() {
    $this->assertEquals(
      'a/foo/path/foo/bar',
      $this->manager->getVendorDirectory()
    );
  }
  
  function testOutputDependenciesOutputsListOfDependenciesAsString() {
    $this->output->expects($this->at(0))
      ->method('out')
      ->with($this->dependency1);
    $this->output->expects($this->at(1))
      ->method('out')
      ->with($this->dependency2);
    $this->manager->outputDependencies();
  }
  
}
