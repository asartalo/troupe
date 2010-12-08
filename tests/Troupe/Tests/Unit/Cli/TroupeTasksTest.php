<?php
namespace Troupe\Tests\Unit\Cli;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Cli\TroupeTasks;

class TroupeTasksTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->manager = $this->quickMock('Troupe\Manager');
    $this->troupe_tasks = new TroupeTasks(
      $this->system_utilities, $this->manager
    );
  }
  
  function testAssemble() {
    $this->manager->expects($this->once())
      ->method('manageDependencies');
    $this->troupe_tasks->taskAssemble();
  }
  
}
