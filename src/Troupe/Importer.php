<?php

namespace Troupe;

use \Troupe\Dependency\Dependency;
use \Troupe\Status\Failure;

const FAIL = 11300;

class Importer {
  
  private $vdm, $utilities;
  
  function __construct(VendorDirectoryManager $vdm, SystemUtilities $utilities) {
    $this->vdm = $vdm;
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
    $this->vdm->link($local_location, $data_location);
  }
  
}
