<?php
namespace Troupe\Tests\Unit\Cli;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Cli\Command;
use \Troupe\Cli\Executor;
use \Troupe\Utilities;


class ExecutorTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->executor = new Executor(new Utilities);
  }
  
  function mockTaskList($methods = array()) {
    return $this->getMock(
      'Troupe\Cli\Tasks',
      array_merge($methods, array('setController', 'getTaskNamespace'))
    );
  }
  
  function testInvokingTaskMethodThroughExecute() {
    $tasks = $this->mockTaskList(array('taskCreateProjectDirectories'));
    $tasks->expects($this->once())
      ->method('taskCreateProjectDirectories')
      ->with($this->equalTo('adirectory'));
    $this->executor->registerTasks($tasks);
    $this->executor->execute(new Command(array(
      'caller'    => '/yo',
      'command'   => 'create-project-directories',
      'arguments' => array('adirectory')
    )));
  }
  
  function testInvokingDuplicateTaskMethods() {
    $tasks1 = $this->mockTaskList(array('taskDummyTask'));
    $tasks2 = $this->mockTaskList(array('taskDummyTask'));
    $tasks1->expects($this->never())
      ->method('taskDummyTask');
    $tasks2->expects($this->once())
      ->method('taskDummyTask')
      ->with($this->equalTo('adirectory'));
    $this->executor->registerTasks($tasks1);
    $this->executor->registerTasks($tasks2);
    $this->executor->execute(new Command(array(
      'caller'    => '/yo',
      'command'   => 'dummy-task',
      'arguments' => array('adirectory')
    )));
  }
  
  function testInvokingTaskMethodWithNameSpace() {
    $tasks = $this->mockTaskList(array('taskCreateProjectDirectories'));
    $tasks->expects($this->once())
      ->method('taskCreateProjectDirectories')
      ->with($this->equalTo('adirectory'));
    $this->executor->registerTasks($tasks, 'foo');
    $this->executor->execute(new Command(array(
      'caller'    => '/yo',
      'namespace' => 'foo',
      'command'   => 'create-project-directories',
      'arguments' => array('adirectory')
    )));
  }
  
  function testInvokingTaskMethodWithoutNamespaceThrowsUndefined() {
    $this->setExpectedException(
		  'Troupe\Cli\Exception\UndefinedTask'
	  );
    $tasks = $this->mockTaskList(array('taskCreateProjectDirectories'));
    $tasks->expects($this->never())
      ->method('taskCreateProjectDirectories');
    $this->executor->registerTasks($tasks, 'foo');
    $this->executor->execute(new Command(array(
      'caller'    => '/yo',
      'command'   => 'create-project-directories',
      'arguments' => array('adirectory')
    )));
  }
  
  function testThrowAsarUtilityCliExceptionWhenTaskMethodIsNotDefined() {
    $this->setExpectedException(
		  'Troupe\Cli\Exception\UndefinedTask',
		  "The task method 'taskSomethingToDoButCannotDo' is not defined."
	  );
	  $this->executor->execute(new Command(array(
	    'caller'  => '/a',
	    'command' => 'something-to-do-but-cannot-do',
	    'arguments' => 'arg1'
	  )));
  }
  
  function testInvokingFlagMethodThroughExecute() {
    $tasks = $this->mockTaskList(array('flagDoSomething'));
    $tasks->expects($this->once())
      ->method('flagDoSomething');
    $this->executor->registerTasks($tasks);
    $this->executor->execute(new Command(array(
      'caller'    => '/yo',
      'flags'   => array('do-something'),
    )));
  }
  
  function testReturningRegisteredTasks() {
    $tasks1 = $this->mockTaskList(array('taskDummyTask1'));
    $tasks2 = $this->mockTaskList(array('taskDummyTask2'));
    $tasks3 = $this->mockTaskList(array('taskDummyTask3'));
    $tasks4 = $this->mockTaskList(array('taskDummyTask4', 'taskDummyTask5'));
    $this->executor->registerTasks($tasks1);
    $this->executor->registerTasks($tasks2, null);
    $this->executor->registerTasks($tasks3, 'foo');
    $this->executor->registerTasks($tasks4, 'bar');
    $this->assertEquals(
      array(
        'dummy-task1', 'dummy-task2', 'foo:dummy-task3',
        'bar:dummy-task4', 'bar:dummy-task5'
      ),
      $this->executor->getRegisteredTasks()
    );
  }
}
