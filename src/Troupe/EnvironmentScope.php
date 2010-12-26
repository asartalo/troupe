<?php

namespace Troupe;

class EnvironmentScope {
  
  private $env, $cwd, $data_dir, $args;
  
  function __construct(array $env, $cwd, $data_dir, array $args) {
    $this->env = $env;
    $this->cwd = $cwd;
    $this->args = $args;
    $this->data_dir = $data_dir;
  }
  
  function getSystemEnvVariables() {
    return $this->env;
  }
  
  function getCwd() {
    return $this->cwd;
  }
  
  function getArgs() {
    return $this->args;
  }
  
  function getDataDirectory() {
    return $this->data_dir;
  }
  
}
