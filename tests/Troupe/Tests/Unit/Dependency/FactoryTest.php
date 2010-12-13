<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Source;
use \Troupe\Source\Factory as SourceFactory;
use \Troupe\Dependency\Factory;
use \Troupe\Dependency\Dependency;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->source = $this->quickMock('Troupe\Source\Source');
    $this->source_factory = $this->quickMock('Troupe\Source\Factory', array('get'));
    $this->project_dir = 'a/path';
    $this->dependency_factory = new Factory(
      $this->source_factory, $this->project_dir
    );
  }
  
  function testGetDependencyUsesSourceFromSourceFactoryAndReturnsDependency() {
    $troupe_list = array(
      'foo' => array(
        'type' => 'svn', 'url'  => 'http://svn.foo.com/repository'
      )
    );
    $this->source_factory->expects($this->once())
      ->method('get')
      ->with($troupe_list['foo']['url'], $troupe_list['foo']['type'])
      ->will($this->returnValue($this->source));
    $dependencies = $this->dependency_factory->getDependencies($troupe_list);
    $this->assertType('array', $dependencies);
    $this->assertType('Troupe\Dependency\Dependency', $dependencies[0]);
    $this->assertEquals(
      new Dependency('foo', $this->source, $this->project_dir . '/vendor' ), $dependencies[0]
    );
  }

}
