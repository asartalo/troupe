<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;
use \Troupe\Expander\Factory as ExpanderFactory;

class Factory {
  
  private
    $system_utilities, 
    $data_directory, 
    $vdm,
    $expander_factory,
    $types = array(
      'svn'     => 'Svn',
      'git'     => 'Git',
      'archive' => 'Archive',
    );
  
  function __construct(SystemUtilities $system_utilities, VDM $vdm, ExpanderFactory $expander_factory, $data_directory = '') {
    $this->system_utilities = $system_utilities;
    $this->vdm = $vdm;
    $this->expander_factory = $expander_factory;
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
    if ($type == 'archive') {
      return new $class($url, $this->vdm, $this->system_utilities, $this->data_directory, $this->expander_factory->getExpander($url));
    }
    return new $class($url, $this->vdm, $this->system_utilities, $this->data_directory);
  }
  
}
