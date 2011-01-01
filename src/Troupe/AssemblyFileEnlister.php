<?php

namespace Troupe;

class AssemblyFileEnlister {
  
  private 
    $project_dir,
    $known_types = array('php', 'ini');
  
  function __construct($project_dir) {
    $this->project_dir = $project_dir;
  }
  
  function getAssemblyFile() {
    foreach ($this->known_types as $ext) {
      $troupe_file = $this->project_dir . '/mytroupe.' . $ext;
      if (file_exists($troupe_file)) {
        return $troupe_file;
      }
    }
    return '';
  }
  
}
