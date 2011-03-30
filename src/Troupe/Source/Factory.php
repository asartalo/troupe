<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\Executor;
use \Troupe\VendorDirectoryManager as VDM;
use \Troupe\Expander\Factory as ExpanderFactory;
use \Cibo;

class Factory {
  
  private
    $system_utilities,
    $executor,
    $data_directory, 
    $vdm,
    $expander_factory,
    $cibo,
    $types = array(
      'svn'     => 'Svn',
      'git'     => 'Git',
      'archive' => 'Archive',
    );
  
  function __construct(
    SystemUtilities $system_utilities,
    Executor $executor, VDM $vdm, ExpanderFactory $expander_factory,
    Cibo $cibo, $data_directory
  ) {
    $this->system_utilities = $system_utilities;
    $this->executor = $executor;
    $this->vdm = $vdm;
    $this->expander_factory = $expander_factory;
    $this->cibo = $cibo;
    $this->data_directory = $data_directory;
  }
  
  function get($url, $type) {
    $class = 'Troupe\Source\\' . (
      isset($this->types[$type]) ? $this->types[$type] : 'Unknown'
    );
    if ($type == 'archive') {
      return new $class(
        $url, $this->vdm, $this->system_utilities, $this->data_directory,
        $this->expander_factory->getExpander($url), $this->cibo
      );
    }
    return new $class($url, $this->vdm, $this->executor, $this->data_directory);
  }
  
}
