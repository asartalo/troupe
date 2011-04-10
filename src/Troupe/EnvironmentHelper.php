<?php

namespace Troupe;

class EnvironmentHelper {
  
  private $cli, $args, $tasklists;
  
  function __construct(\Troupe\Cli\Controller $cli, array $args, array $tasklists) {
    $this->cli = $cli;
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
