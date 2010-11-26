<?php

namespace Troupe;

use \Troupe\Dependency;

class Importer {
  
  private $utilities;
  
  function __construct(SystemUtilities $utilities) {
    $this->utilities = $utilities;
  }
  
  function import($project_root_dir, Dependency $dependency) {
    $dependency->load();
  }
  
}