<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;

class Factory {
  
  private
    $system_utilities, 
    $data_directory, 
    $vdm,
    $types = array(
      'svn' => 'Svn',
      'git' => 'Git',
    );
  
  function __construct(SystemUtilities $system_utilities, VDM $vdm, $data_directory = '') {
    $this->system_utilities = $system_utilities;
    $this->vdm = $vdm;
    // TODO: Refactor this
    if (!$data_directory) {
      $data_directory = realpath(__DIR__ . '/../../../data');
    }
    $this->data_directory = $data_directory;
  }
  
  function get($url, $type) {
    $class = 'Troupe\Source\\' . (
      isset($this->types[$type]) ? $this->types[$type] : 'Unknown'
    );
    return new $class($url, $this->vdm, $this->system_utilities, $this->data_directory);
  }
  
}
