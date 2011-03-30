<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Factory;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->executor = $this->getMock('Troupe\Executor');
    $this->data_directory = 'a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager');
    $this->expander_factory = $this->quickMock('Troupe\Expander\Factory');
    $this->cibo = $this->quickMock('Cibo');
    $this->expander = $this->quickMock('Troupe\Expander\Expander');
    $this->expander_factory->expects($this->any())
      ->method('getExpander')
      ->will($this->returnValue($this->expander));
    $this->source_factory = new Factory(
      $this->system_utilities, $this->executor, $this->vdm,
      $this->expander_factory, $this->cibo, $this->data_directory
    );
  }
  
  function testGetUnknownSource() {
    $source = $this->source_factory->get('http://example/uri', 'unknown_type');
    $this->assertInstanceOf('Troupe\Source\Unknown', $source);
    $source = $this->source_factory->get('http://example2/uri', 'boooo');
    $this->assertInstanceOf('Troupe\Source\Unknown', $source);
  }
  
  function testGetSvnSource() {
    $source = $this->source_factory->get('http://example/svn/repo', 'svn');
    $this->assertInstanceOf('Troupe\Source\Svn', $source);
  }
  
  function testGetGitSource() {
    $source = $this->source_factory->get('git://example/foo.git/', 'git');
    $this->assertInstanceOf('Troupe\Source\Git', $source);
  }
  
  function testGetZipArchiveSource() {
    $source = $this->source_factory->get('http://example/foo.zip', 'archive');
    $this->assertInstanceOf('Troupe\Source\Archive', $source);
  }
  
}
