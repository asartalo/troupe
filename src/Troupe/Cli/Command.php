<?php

namespace Troupe\Cli;

class Command {
  
  private
    $caller,
    $namespace,
    $command,
    $flags = array(),
    $arguments = array();
  
  function __construct(array $options) {
    foreach(
      array('caller', 'command', 'namespace', 'flags', 'arguments')
      as $name
    ) {
      $this->setIfExists($name, $options);
    }
  }
  
  private function setIfExists($name, $array) {
    if (array_key_exists($name, $array)) {
      $this->$name = $array[$name];
    }
  }
  
  function getCaller() {
    return $this->caller;
  }
  
  function getNamespace() {
    return $this->namespace;
  }
  
  function getCommand() {
    return $this->command;
  }
  
  function getFlags() {
    return $this->flags;
  }
  
  function getArguments() {
    return $this->arguments;
  }
  
}
