<?php

namespace Troupe\Reader;

use \Troupe\File\File;
use \Troupe\SystemUtilities;

class Php implements Reader {
  
  private
    $project_dir,
    $system_utilities,
    $assembly_file,
    $list_cache;
  
  function __construct(File $assembly_file, SystemUtilities $system_utilities) {
  	$this->assembly_file = $assembly_file;
    $this->system_utilities = $system_utilities;
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
      $this->list_cache = $this->assembly_file->isFileExists() ?
        $sys->includeFile($this->assembly_file->getPath()) : array();
    }
    return $this->list_cache;
  }
  
}
