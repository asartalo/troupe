<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Dependency\Dependency;
use \Troupe\Source\Source;
use \Troupe\Status\Success;

class DependencyTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->project_name = 'foo';
    $this->source = $this->getMock('Troupe\Source\Source'); 
    $this->local_dir = 'path/to/project';
    $this->alias = 'foo_bar';
    $this->dependency = new Dependency(
      $this->project_name, $this->source, $this->local_dir, $this->alias
    );
  }
  
  function testLoadCallsSourceImport() {
    $this->source->expects($this->once())
      ->method('import');
    $this->dependency->load();
  }
  
  function testLoadReturnsValueFromImport() {
    $status = new Success(
      \Troupe\Source\STATUS_OK, "Imported.", 'a/path/to/a/library'
    );
    $this->source->expects($this->once())
      ->method('import')
      ->will($this->returnValue($status));
    $this->assertEquals($status, $this->dependency->load());
  }
  
  function testLocalLocation() {
    $this->assertEquals(
      'path/to/project/foo_bar', $this->dependency->getLocalLocation()
    );
  }
  
  function testLocalLocationWhenNoAliasIsSpecified() {
    $this->dependency = new Dependency(
      $this->project_name, $this->source, $this->local_dir
    );
    $this->assertEquals(
      'path/to/project/foo', $this->dependency->getLocalLocation()
    );
  }
  
  function testGetDataLocationCallsSourceGetDataDir() {
    $this->source->expects($this->once())
      ->method('getDataDir');
    $this->dependency->getDataLocation();
  }
  
  function testGetDataLocationReturnsValueFromSourceGetDataDir() {
    $this->source->expects($this->once())
      ->method('getDataDir')
      ->will($this->returnValue('foo'));
    $this->assertEquals('foo', $this->dependency->getDataLocation());
  }
  
}
