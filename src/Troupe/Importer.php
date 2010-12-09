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
    $data_location = $dependency->getDataLocation();
    $local_location = $dependency->getLocalLocation();
    $status = $dependency->load();
    if ($status->isSuccessful()) {    
      $this->linkLocations($data_location, $local_location);
    }
    $this->utilities->out($status->getMessage());
    return $status;
  }
  
  private function linkLocations($data_location, $local_location) {
    if ($this->utilities->fileExists($local_location)) {
      if ($this->isLinkPointsTo($local_location, $data_location)) {
        return;
      } else {
        $this->utilities->unlink($local_location);
      }
    }
    $this->utilities->symlink($data_location, $local_location);
  }
  
  private function isLinkPointsTo($local_location, $data_location) {
    return $data_location === $this->utilities->readlink($local_location);
  }
  
}
