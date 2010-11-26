<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Cli\Command;
use \Troupe\Cli\Interpreter;


class DependencyTest extends \PHPUnit_Framework_TestCase {
  
  function testLoad() {
    $this->markTestIncomplete();
    $this->dependency->load();
  }
  
}