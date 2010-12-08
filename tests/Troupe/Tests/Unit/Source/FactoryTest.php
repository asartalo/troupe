<?php
namespace Troupe\Tests\Unit\Source;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Source\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->data_directory = 'a/directory';
    $this->source_factory = new Factory(
      $this->system_utilities, $this->data_directory
    );
  }
  
  function testGetSvnSource() {
    $source = $this->source_factory->get('http://example/svn/repo', 'svn');
    $this->assertType('Troupe\Source\Svn', $source);
  }
  
  function testGetUnknownSource() {
    $source = $this->source_factory->get('http://example/uri', 'unknown_type');
    $this->assertType('Troupe\Source\Unknown', $source);
    $source = $this->source_factory->get('http://example2/uri', 'boooo');
    $this->assertType('Troupe\Source\Unknown', $source);
  }
  
}
