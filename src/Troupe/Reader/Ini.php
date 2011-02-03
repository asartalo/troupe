<?php

namespace Troupe\Reader;

use \Troupe\File\File;
use \Troupe\SystemUtilities;

class Ini implements Reader {
  
  private
    $system_utilities,
    $assembly_file;
  
  function __construct(File $assembly_file, SystemUtilities $system_utilities) {
  	$this->assembly_file = $assembly_file;
    $this->system_utilities = $system_utilities;
  }
  
  function getDependencyList() {
    $list = parse_ini_string($this->assembly_file->getContents(), true);
    unset($list['_settings']);
    return $list;
  }
  
  function getSettings() {
    $list = parse_ini_string($this->assembly_file->getContents(), true);
    return isset($list['_settings']) ? $list['_settings'] : array();
  }
  
  
}
