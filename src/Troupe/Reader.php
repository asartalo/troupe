<?php

namespace Troupe;

class Reader {
  
  private $project_dir, $system_utilities;
  
  function __construct($project_dir, SystemUtilities $system_utilities) {
    $this->project_dir = $project_dir;
    $this->system_utilities = $system_utilities;
  }
  
  function read() {
    $sys = $this->system_utilities;
    $assembly_file = $this->project_dir . '/' . 'mytroupe.php';
    if ($sys->fileExists($assembly_file)) {
      return $sys->includeFile($assembly_file);
    }
    return array();
  }
  
}
