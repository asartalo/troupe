<?php

namespace Troupe;

use \Troupe\Dependency\Dependency;
use \Troupe\Status\Failure;
use \Troupe\VendorDirectory\Manager as VDM;

const FAIL = 11300;

class Importer {
  
  private $vdm, $output;
  
  function __construct(VDM $vdm, Output $output) {
    $this->vdm = $vdm;
    $this->output = $output;
  }
  
  // TODO: Refactor this.
  function import($project_vendor_dir, Dependency $dependency) {
    $data_location = $dependency->getDataLocation();
    $local_location = $dependency->getLocalLocation();
    $status = $dependency->load();
    if ($status->isSuccessful()) {
      $this->linkLocations($data_location, $local_location);
    }
    $this->output->out($status->getMessage());
    return $status;
  }
  
  private function linkLocations($data_location, $local_location) {
    $this->vdm->link($local_location, $data_location);
  }
  
}
