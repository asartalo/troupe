<?php
namespace Troupe\Tests\Unit\Cli;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Cli\Command;
use \Troupe\Cli\Executor;
use \Troupe\Cli\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase {
  
  function setUp() {
    // This is called to help the reflector
    $this->getMock('Troupe\Cli\Tasks');
    $this->interpreter = $this->getMock('Troupe\Cli\Interpreter');
    $this->executor = $this->getMock(
      'Troupe\Cli\Executor', array(), array(), '', false
    );
    $this->dir = realpath(dirname(__FILE__));
    $this->cli = new Controller($this->interpreter, $this->executor, $this->dir);
  }
  
  function mock($methods = array()) {
    return $this->getMock('\Troupe\Cli\Controller', $methods, array(), '', false);
  }
  
  function mockTaskList($methods = array()) {
    return $this->getMock(
      'Troupe\Cli\Tasks',
      array_merge($methods, array('setController', 'getTaskNamespace'))
    );
  }

  function testExecutePassesArgumentsToInterpreter() {
    $arguments = array(
      '/cli/ui/front', '--aflag'
    );
    $command = new Command(array());
    $this->interpreter->expects($this->once())
      ->method('interpret')
      ->with($this->equalto($arguments))
      ->will($this->returnValue($command));
    $this->cli->execute($arguments);
  }
  
  function testExecutePassesCommandFromInterpreterToExecutor() {
    $command = new Command(array());
    $this->interpreter->expects($this->once())
      ->method('interpret')
      ->will($this->returnValue($command));
    $this->executor->expects($this->once())
      ->method('execute')
      ->with($command);
    $this->cli->execute(array());
  }
  
  function testRegisterPassesItselfToTaskListAsController() {
    $tasks = $this->mockTaskList();
    $tasks->expects($this->once())
      ->method('setController')
      ->with($this->equalTo($this->cli));
    $this->executor->expects($this->once())
      ->method('registerTasks')
      ->with($tasks);
    $this->cli->register($tasks);
  }
  
  function testRegisterPassesNamespaceToExecutorRegisterTasks() {
    $tasks = $this->getMock('Troupe\Cli\Tasks');
    $tasks->expects($this->once())
      ->method('getTaskNamespace')
      ->will($this->returnValue('foo'));
    $this->executor->expects($this->once())
      ->method('registerTasks')
      ->with($tasks, 'foo');
    $this->cli->register($tasks);
  }
  
  function testOutput() {
    $test_string  = 'Foo bar.';
    $test_string2 = 'Bar foo.';
    ob_start();
    $this->cli->out($test_string);
    $this->cli->out($test_string2);
    $out = ob_get_clean();
    $this->assertEquals("$test_string\n$test_string2\n", $out);
  }
  
  function testGetRegisteredTasks() {
    $tasks = array('foo', 'bar');
    $this->executor->expects($this->once())
      ->method('getRegisteredTasks')
      ->will($this->returnValue($tasks));
    $this->assertEquals($tasks, $this->cli->getRegisteredTasks());
  }
  
  function testGettingWorkingDirectory() {
    $this->assertEquals(
      $this->dir, $this->cli->getWorkingDirectory()
    );
  }
  
}
