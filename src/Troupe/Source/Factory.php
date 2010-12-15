<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;

class Factory {
  
  private
    $system_utilities, 
    $data_directory, 
    $types = array(
      'svn' => 'Svn',
      'git' => 'Git',
    );
  
  function __construct(SystemUtilities $system_utilities, $data_directory = '') {
    $this->system_utilities = $system_utilities;
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
    return new $class($url, $this->system_utilities, $this->data_directory);
  }
  
}
