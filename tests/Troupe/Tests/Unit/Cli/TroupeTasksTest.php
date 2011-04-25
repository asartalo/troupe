<?php
namespace Troupe\Tests\Unit\Cli;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');
require_once realpath(__DIR__ . '/../../CheckOutput.php');

use \Troupe\Cli\TroupeTasks;

class TroupeTasksTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->output = new \Troupe\Tests\CheckOutput;
    $this->manager = $this->quickMock('Troupe\Manager');
    $this->troupe_tasks = new TroupeTasks(
      $this->output, $this->manager
    );
  }
  
  function testAssemble() {
    $this->manager->expects($this->once())
      ->method('importDependencies');
    $this->troupe_tasks->taskAssemble();
  }
  
  function testList() {
    $this->manager->expects($this->once())
      ->method('outputDependencies');
    $this->troupe_tasks->taskList();
  }
  
  function testUpdate() {
    $this->manager->expects($this->once())
      ->method('updateDependencies');
    $this->troupe_tasks->taskUpdate();
  }
  
}
