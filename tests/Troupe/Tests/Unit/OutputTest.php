<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Output;

class OutputTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->output = new Output;
  }
  
  function testOutEchoesStringPlusNewLine() {
    ob_start();
    $this->output->out("Foo Bar");
    $this->assertEquals("Foo Bar\n", ob_get_clean());
  }

}
