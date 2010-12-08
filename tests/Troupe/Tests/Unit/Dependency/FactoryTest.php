<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Source;
use \Troupe\Source\Factory as SourceFactory;
use \Troupe\Dependency\Factory;
use \Troupe\Dependency\Dependency;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->source_factory = new SourceFactory(
      $this->system_utilities, 'data/dir'
    );
    $this->project_dir = 'a/path';
    $this->dependency_factory = new Factory(
      $this->source_factory, $this->project_dir
    );
  }
  
  private function getSource($options) {
    return $this->source_factory->get(
      $options['url'], $options['type']
    );
  }
  
  function testGetDependencyUsesSourceFromSourceFactoryAndReturnsDependency() {
    $troupe_list = array(
      'foo' => array(
        'type' => 'svn', 'url'  => 'http://svn.foo.com/repository'
      )
    );
    $source = $this->getSource($troupe_list['foo']);
    $dependencies = $this->dependency_factory->getDependencies($troupe_list);
    $this->assertType('array', $dependencies);
    $this->assertType('Troupe\Dependency\Dependency', $dependencies[0]);
    $this->assertEquals(
      new Dependency('foo', $source, $this->project_dir . '/vendor' ), $dependencies[0]
    );
  }

}
