<?php

namespace Troupe\Cli;

use \Troupe\Cli\Command;
use \Troupe\Utilities;
use \Troupe\Cli\Exception\UndefinedTask as ExceptionUndefinedTask;

class Executor {

  private
    $utilities,
    $tasklists_n = array(),
    $tasklists = array();

  function __construct(Utilities $utilities) {
    $this->utilities = $utilities;
  }
  
  function registerTasks(Tasks $tasks, $namespace = null) {
    if ($namespace) {
      $this->tasklists_n[$namespace] = $tasks;
    } else {
      $this->tasklists[] = $tasks;
    }
  }
  
  function execute(Command $command) {
    $method = 'task' . $this->utilities->camelCase($command->getCommand());
    $method_called = false;
    if (array_key_exists($command->getNamespace(), $this->tasklists_n)) {
      $method_called = $this->invokeTaskMethod(
        $this->tasklists_n[$command->getNamespace()], $method,
        $command->getArguments()
      );
    } else {
      $tasklistsr = array_reverse($this->tasklists, true);
      foreach ($tasklistsr as $tasklist) {
        $method_called = $this->invokeTaskMethod(
          $tasklist, $method, $command->getArguments()
        );
        if ($method_called) break;
      }
    }
    if ($command->getCommand() && !$method_called) {
      throw new ExceptionUndefinedTask(
        "The task method '$method' is not defined."
      );
    }
    if ($command->getFlags()) {
      foreach ($command->getFlags() as $flag) {
        $this->executeFlag($flag);
      }
    }
  }
  
  function getRegisteredTasks() {
    $list = array();
    $alltasks = array_merge($this->tasklists, $this->tasklists_n);
    foreach ($alltasks as $namespace => $tasklist) {
      $refclass = new \ReflectionClass(get_class($tasklist));
      $methods = $refclass->getMethods();
      foreach ($methods as $method) {
        $name = $method->getName();
        if (strpos($name, 'task') === 0) {
          $n_name = $this->utilities->dashLowerCase(substr($name, 4));
          $list[] = is_string($namespace) ? "$namespace:$n_name" : $n_name;
        }
      }
    }
    return $list;
  }
  
  private function executeFlag($flag) {
    $tasklists = $this->getAllTaskLists();
    foreach ($tasklists as $tasklist) {
      $method = 'flag' . $this->utilities->camelCase($flag);
      $this->invokeTaskMethod($tasklist, $method, array());
    }
  }
  
  private function getAllTaskLists() {
    return array_merge($this->tasklists, $this->tasklists_n);
  }
  
  private function invokeTaskMethod($tasklist, $method, $args) {
    if (method_exists($tasklist, $method)) {
      call_user_func_array(
        array($tasklist, $method), $args
      );
      return true;
    }
    return false;
  }
  
}
