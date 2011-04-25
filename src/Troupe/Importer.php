<?php

namespace Troupe;

use \Troupe\Dependency\DependencyInterface;
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
  function import($project_vendor_dir, DependencyInterface $dependency) {
    $status = $dependency->import();
    if ($status->isSuccessful()) {
      $this->linkLocations(
        $dependency->getDataLocation(),$dependency->getLocalLocation()
      );
    }
    $this->output->out($status->getMessage());
    return $status;
  }
  
  function update($project_vendor_dir, DependencyInterface $dependency) {
    $status = $dependency->update();
    $this->output->out($status->getMessage());
    return $status;
  }
  
  private function linkLocations($data_location, $local_location) {
    $this->vdm->link($local_location, $data_location);
  }
  
}
