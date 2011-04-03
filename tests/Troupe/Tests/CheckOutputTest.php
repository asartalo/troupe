<?php

namespace Troupe\Tests;

require_once realpath(__DIR__ . '/../../bootstrap.php');
require_once realpath(__DIR__ . '/CheckOutput.php');

class CheckOutputTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->output = new CheckOutput;
  }
  
  function testCheckOutputIsInstanceOfOutput() {
    $this->assertInstanceOf('Troupe\Output', $this->output);
  }
  
  function testOutDoesNotEchoAnything() {
    ob_start();
    $this->output->out("Foo");
    $this->assertEquals('', ob_get_clean());
  }
  
  function testCheckOutputStoresEverything() {
    $this->output->out('Foo');
    $this->output->out('Bar');
    $this->output->out('Baz');
    $this->assertEquals("Foo\nBar\nBaz\n", $this->output->getOutput());
  }

}
