<?php

namespace Troupe;

class Reader {
  
  private
    $project_dir,
    $system_utilities,
    $assembly_file,
    $list_cache;
  
  function __construct($project_dir, SystemUtilities $system_utilities) {
    $this->project_dir = $project_dir;
    $this->system_utilities = $system_utilities;
    $this->assembly_file = $this->project_dir . '/' . 'mytroupe.php';
  }
  
  function getDependencyList() {
    $list = $this->getAllTroupeList();
    unset($list['_settings']);
    return $list;
  }
  
  function getSettings() {
    $list = $this->getAllTroupeList();
    return isset($list['_settings']) ? $list['_settings'] : array();
  }
  
  private function getAllTroupeList() {
    if (!$this->list_cache) {
      $sys = $this->system_utilities;
      $this->list_cache = $sys->fileExists($this->assembly_file) ?
        $sys->includeFile($this->assembly_file) : array();
    }
    return $this->list_cache;
  }
  
}
