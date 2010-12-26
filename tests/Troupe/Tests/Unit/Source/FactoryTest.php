<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Factory;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->data_directory = 'a/directory';
    $this->vdm = $this->quickMock('Troupe\VendorDirectoryManager');
    $this->source_factory = new Factory(
      $this->system_utilities, $this->vdm, $this->data_directory
    );
  }
  
  function testGetSvnSource() {
    $source = $this->source_factory->get('http://example/svn/repo', 'svn');
    $this->assertInstanceOf('Troupe\Source\Svn', $source);
  }
  
  function testGetUnknownSource() {
    $source = $this->source_factory->get('http://example/uri', 'unknown_type');
    $this->assertInstanceOf('Troupe\Source\Unknown', $source);
    $source = $this->source_factory->get('http://example2/uri', 'boooo');
    $this->assertInstanceOf('Troupe\Source\Unknown', $source);
  }
  
  function testGetGitSource() {
    $source = $this->source_factory->get('git://example/foo.git/', 'git');
    $this->assertInstanceOf('Troupe\Source\Git', $source);
  }
  
}
