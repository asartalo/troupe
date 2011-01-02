<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Factory;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->factory = new Factory;
  }
  
  function testGettingZipExpander() {
    $this->assertInstanceOf(
      'Troupe\Expander\Zip', 
      $this->factory->getExpander('http://example.com/ziparchive.zip')
    );
  }

}
