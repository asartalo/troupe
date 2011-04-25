<?php

namespace Troupe\Dependency;

use \Troupe\Source\Source;

class Dependency implements DependencyInterface {
  
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
  
  function import() {
    return $this->source->import();
  }
  
  function update() {
    return $this->source->update();
  }
  
  function getSource() {
    return $this->source;
  }
  
  function getDataLocation() {
    return $this->source->getDataDir();
  }
  
  function getUrl() {
    return $this->source->getUrl();
  }
  
  function __toString() {
    if ($this->alias) {
      return "{$this->name} ({$this->alias}) : {$this->getUrl()}";
    } else {
      return "{$this->name} : {$this->getUrl()}";
    }
  }
  
}
