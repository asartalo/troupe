<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Dependency\Dependency;
use \Troupe\Source\Source;
use \Troupe\Status\Success;

class DependencyTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->name = 'foo';
    $this->source = $this->getMock('Troupe\Source\Source'); 
    $this->local_dir = 'path/to/project';
    $this->alias = 'foo_bar';
    $this->dependency = new Dependency(
      $this->name, $this->source, $this->local_dir, $this->alias
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
      $this->name, $this->source, $this->local_dir
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
  
  function testGetUrlReturnsUrlFromSource() {
    $this->source->expects($this->once())
      ->method('getUrl')
      ->will($this->returnValue('http://foo.com/foo'));
    $this->assertEquals('http://foo.com/foo', $this->dependency->getUrl());
  }
  
  function testToStringOutputsNameWithAliasAndUrl() {
    $this->source->expects($this->once())
      ->method('getUrl')
      ->will($this->returnValue('http://foo.com/foo'));
    $this->assertEquals(
      "{$this->name} ({$this->alias}) : http://foo.com/foo",
      $this->dependency->__toString()
    );
  }
  
  function testToStringOutputsOnlyNameAndUrlWhenNoAliasIsDefined() {
    $this->dependency = new Dependency(
      $this->name, $this->source, $this->local_dir
    );
    $this->source->expects($this->once())
      ->method('getUrl')
      ->will($this->returnValue('http://foo.com/foo'));
    $this->assertEquals(
      "{$this->name} : http://foo.com/foo",
      $this->dependency->__toString()
    );
  }
  
}
