<?php

namespace Troupe\Dependency;

use \Troupe\Source\Source;

class Dependency {
  
  private $name, $source, $local_dir, $alias;
  
  function __construct($name, Source $source, $local_dir, $alias = '') {
    $this->name = $name;
    $this->source = $source;
    $this->local_dir = $local_dir;
    $this->alias = $alias;
  }
  
  function getName() {
    return $this->name;
  }
  
  function getLocalLocation() {
    return $this->local_dir . DIRECTORY_SEPARATOR . 
      ($this->alias ? $this->alias : $this->name);
  }
  
  function load() {
    return $this->source->import();
  }
  
  function getSource() {
    return $this->source;
  }
  
  function getDataLocation() {
    return $this->source->getDataDir();
  }
  
}
