<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Importer;

class ImporterTest extends \PHPUnit_Framework_TestCase {
  
  public function setUp() {
    $this->project_dir = 'foo/path';
    $this->dependency = $this->dependency1 = $this->getMock('Troupe\Dependency', array(), array(), '', false);
    $this->utilities = $this->getMock('Troupe\SystemUtilities', array('symlink'));
    $this->importer = new Importer($this->utilities);
  }
  
  function testImportingDependency() {
    $this->dependency->expects($this->once())
      ->method('load');
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
  function testImportRetrievesValueFromLoad() {
    $dep_path   = 'a/path/to/the/dependency';
    $local_path = 'lib/path/my_dependency';
    $this->dependency->expects($this->once())
      ->method('load')
      ->will($this->returnValue($dep_path));
    $this->dependency->expects($this->once())
      ->method('getLocalLocation')
      ->will($this->returnValue($local_path));
    $this->utilities->expects($this->once())
      ->method('symlink')
      ->with($dep_path, $this->project_dir . '/' . $local_path);
    $this->importer->import($this->project_dir, $this->dependency);
  }
  
}