<?php
namespace Troupe\Tests\Unit\Dependency;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

require_once 'Cibo/Cibo.php';
use \Troupe\Source\Source;
use \Troupe\Dependency\Container;
use \Troupe\Dependency\Dependency;
use \Troupe\Settings;
use \Troupe\DefaultSettingsValues;

class ContainerTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->project_dir = 'a/path';
    $defaults = new DefaultSettingsValues($this->project_dir);
    $this->settings = new Settings($defaults->getValues());
    $this->vdm = $this->quickMock('Troupe\VendorDirectory\Manager');
    $this->executor = $this->quickMock('Troupe\Executor');
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->data_directory = 'data/dir';
    $this->options = array(
      'type' => 'svn', 'url'  => 'http://svn.foo.com/repository'
    );
    $this->container = new Container(
      'foo', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
  }

  function testDependencyReturnsDependency() {
    $this->assertInstanceOf(
      'Troupe\Dependency\Dependency',
      $this->container['Dependency']
    );
  }

  function testDependencySetsDefaultLocation() {
    $container = new Container(
      'foo', 'another/path', $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertEquals(
      'another/path/vendor/foo',
      $container['Dependency']->getLocalLocation()
    );
  }

  function testDependencySetsAliasedtLocation() {
    $this->options['alias'] = 'bar';
    $container = new Container(
      'foo', 'another/path', $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertEquals(
      'another/path/vendor/bar',
      $container['Dependency']->getLocalLocation()
    );
  }

  function testDependencyReturnsDependencyWithSource() {
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $container['Source'] = $source = new \Troupe\Source\Unknown;
    $this->assertSame($source, $container['Dependency']->getSource());
  }

  function testSourceReturnsUnknownSourceByDefault() {
    unset($this->options['type']);
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertInstanceOf(
      'Troupe\Source\Unknown', $container['Dependency']->getSource()
    );
  }

  function testSourceReturnsSvnSourceWhenTypeIsSvn() {
    $this->assertInstanceOf(
      'Troupe\Source\Svn', $this->container['Dependency']->getSource()
    );
  }

  function testSourceReturnsGitSourceWhenTypeIsGit() {
    $this->options['type'] = 'git';
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertInstanceOf(
      'Troupe\Source\Git', $container['Dependency']->getSource()
    );
  }

  function testSourceReturnsFileSourceWhenTypeIsFile() {
    $this->options['type'] = 'file';
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertInstanceOf(
      'Troupe\Source\File', $container['Dependency']->getSource()
    );
  }

  function testSourceReturnsSourceArchiveWhenTypeIsArchiveWithExpander() {
    $this->options['type'] = 'archive';
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $container['Expander'] = $this->quickMock('Troupe\Expander\Expander');
    $this->assertInstanceOf(
      'Troupe\Source\Archive', $container['Dependency']->getSource()
    );
  }

  function testExpanderReturnsNullExpanderByDefault() {
    unset($this->options['url']);
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertInstanceOf(
      'Troupe\Expander\NullExpander', $container['Expander']
    );
  }

  /**
   * @dataProvider dataExpanderReturnsCorrectExpanderType
   */
  function testExpanderReturnsCorrectExpanderType($extension, $type) {
    $this->options['url'] = "http://www.foo.com/path/to/file.$extension";
    $container = new Container(
      'bar', $this->project_dir, $this->settings, $this->options,
      $this->vdm, $this->executor, $this->system_utilities,
      $this->data_directory
    );
    $this->assertInstanceOf(
      "Troupe\Expander\\$type", $container['Expander']
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
