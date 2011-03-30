<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Source;
use \Troupe\Dependency\Container;
use \Troupe\Dependency\Dependency;
use \Troupe\Settings;

class ContainerTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->project_dir = 'a/path';
    $this->settings = new Settings;
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager');
    $this->executor = $this->quickMock('Troupe\Executor');
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->data_directory = 'data/dir';
    $this->options = array(
      'type' => 'svn', 'url'  => 'http://svn.foo.com/repository'
    );
  }
  
  private function getContainer($name = 'foo') {
    return new Container(
      $name, $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
  }
  
  function testDependencyReturnsDependency() {
    $this->assertInstanceOf(
      'Troupe\Dependency\Dependency', $this->getContainer()->Dependency
    );
  }
  
  function testDependencySetsDefaultLocation() {
    $this->project_dir = 'another/path';
    $this->assertEquals(
      'another/path/vendor/foo',
      $this->getContainer()->Dependency->getLocalLocation()
    );
  }
  
  function testDependencySetsAliasedtLocation() {
    $this->options['alias'] = 'bar';
    $this->project_dir = 'another/path';
    $this->assertEquals(
      'another/path/vendor/bar',
      $this->getContainer()->Dependency->getLocalLocation()
    );
  }
  
  function testDependencyReturnsDependencyWithSource() {
    $container = $this->getContainer('bar');
    $container->Source = $source = new \Troupe\Source\Unknown;
    $this->assertSame($source, $container->Dependency->getSource());
  }

  function testSourceReturnsUnknownSourceByDefault() {
    unset($this->options['type']);
    $this->assertInstanceOf(
      'Troupe\Source\Unknown', $this->getContainer()->Dependency->getSource()
    );
  }
  
  function testSourceReturnsSvnSourceWhenTypeIsSvn() {
    $this->assertInstanceOf(
      'Troupe\Source\Svn', $this->getContainer()->Dependency->getSource()
    );
  }
  
  function testSourceReturnsGitSourceWhenTypeIsGit() {
    $this->options['type'] = 'git';
    $this->assertInstanceOf(
      'Troupe\Source\Git', $this->getContainer()->Dependency->getSource()
    );
  }
  
  function testSourceReturnsSourceArchiveWhenTypeIsArchiveWithExpander() {
    $this->options['type'] = 'archive';
    $container = $this->getContainer();
    $container->Expander = $this->quickMock('Troupe\Expander\Expander');
    $this->assertInstanceOf(
      'Troupe\Source\Archive', $container->Dependency->getSource()
    );
  }
  
  function testExpanderReturnsNullExpanderByDefault() {
    unset($this->options['url']);
    $this->assertInstanceOf(
      'Troupe\Expander\NullExpander', $this->getContainer()->Expander
    );
  }
  
  /**
   * @dataProvider dataExpanderReturnsCorrectExpanderType
   */
  function testExpanderReturnsCorrectExpanderType($extension, $type) {
    $this->options['url'] = "http://www.foo.com/path/to/file.$extension";
    $this->assertInstanceOf(
      "Troupe\Expander\\$type", $this->getContainer()->Expander
    );
  }
  
  function dataExpanderReturnsCorrectExpanderType() {
    return array(
      array('zip', 'Zip'),
      array('tar', 'Tar'),
      array('gzip', 'Gzip'),
      array('gz', 'Gzip'),
      array('tgz', 'Tgz'),
      array('tar.gz', 'Tgz'),
    );
  }
  
}
