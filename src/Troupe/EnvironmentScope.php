<?php

namespace Troupe;

class EnvironmentScope {
  
  private $env, $cwd, $args;
  
  function __construct(array $env, $cwd, array $args) {
    $this->env = $env;
    $this->cwd = $cwd;
    $this->args = $args;
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
  
}
