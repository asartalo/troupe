<?php

namespace Troupe;

class Dependency {
  
  private $name, $source, $local_dir, $alias;
  
  function __construct($name, \Troupe\Source $source, $local_dir, $alias) {
    $this->name = $name;
    $this->source = $source;
    $this->local_dir = $local_dir;
    $this->$alias = $alias;
  }
  
  function getName() {
    return $this->name;
  }
  
  function getLocalLocation() {
    return $this->local_dir . DIRECTORY_SEPARATOR . $alias;
  }
  
  function load() {
    
  }
  
}
