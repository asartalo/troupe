<?php

namespace Troupe;

class EnvironmentScope {
  
  private $env, $cwd, $data_dir, $args, $cache = array();
  
  function __construct(array $env, $cwd, $data_dir, array $args) {
    $this->env = $env;
    $this->cwd = $cwd;
    $this->args = $args;
    $this->data_dir = $data_dir;
  }
  
  function addToCache($name, $object) {
    $this->cache[$name] = $object;
  }
  
  function getCache($name) {
    if (!isset($this->cache[$name])) {
      throw new Exception("Cannot find '$name' in cache.");
    }
    return $this->cache[$name];
  }
  
  function isInCache($name) {
    return isset($this->cache[$name]);
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
