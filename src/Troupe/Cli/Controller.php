<?php

namespace Troupe\Cli;

class Controller {
  
  private $interpreter, $executor, $cwd, $out_first;
  
  function __construct(Interpreter $interpreter, Executor $executor, $cwd) {
    $this->interpreter = $interpreter;
    $this->executor = $executor;
    $this->cwd = $cwd;
  }
  
  function execute(array $arguments) {
    $this->executor->execute($this->interpreter->interpret($arguments));
  }
  
  function register(Tasks $tasklist) {
    $this->executor->registerTasks($tasklist, $tasklist->getTaskNamespace());
    $tasklist->setController($this);
  }
  
  function getRegisteredTasks() {
    return $this->executor->getRegisteredTasks();
  }
  
  function out($string) {
      echo $string,"\n";
  }
  
  function getWorkingDirectory() {
    return $this->cwd;
  }
  
}
