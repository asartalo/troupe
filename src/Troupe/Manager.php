<?php

namespace Troupe;

use \Troupe\Importer;

class Manager {
  
  private $project_root_dir, $dependencies, $importer;
  
  function __construct($project_root_dir, array $dependencies, Importer $importer) {
    $this->project_root_dir = $project_root_dir;
    $this->dependencies = $dependencies;
    $this->importer = $importer;
  }
  
  function getDependencies() {
    return $this->dependencies;
  }
  
  function manageDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->importer->import($this->project_root_dir, $dependency);
    }
  }
  
}