<?php

namespace Troupe;

use \Troupe\Dependency\Dependency;
use \Troupe\Status\Failure;

const FAIL = 11300;

class Importer {
  
  private $utilities;
  
  function __construct(SystemUtilities $utilities) {
    $this->utilities = $utilities;
  }
  
  // TODO: Refactor this.
  function import($project_vendor_dir, Dependency $dependency) {
    $status = $dependency->load();
    if ($status->isSuccessful()) {    
      $lib_location = $status->getAttachment();
      $dep_local = $dependency->getLocalLocation();
      if ($lib_location !== $this->utilities->readlink($dep_local)) {
        $this->utilities->symlink($lib_location, $dep_local);
      }
    }
    $this->utilities->out($status->getMessage());
    return $status;
  }
  
}
