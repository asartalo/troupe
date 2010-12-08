<?php

namespace Troupe;

class EnvironmentHelper {
  
  private $cli, $env, $cwd, $args, $tasklists;
  
  function __construct(\Troupe\Cli\Controller $cli, array $env, $cwd, array $args, array $tasklists) {
    $this->cli = $cli;
    $this->env = $env;
    $this->cwd = $cwd;
    $this->args = $args;
    $this->tasklists = $tasklists;
  }
  
  function run() {
    foreach ($this->tasklists as $tasklist) {
      $this->cli->register($tasklist);
    }
    $this->cli->execute($this->args);
  }
  
}
