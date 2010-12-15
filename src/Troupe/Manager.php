<?php

namespace Troupe;

use \Troupe\Importer;
use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;

class Manager {
  
  private $project_root_dir, $dependencies, $importer, $system_utilities;
  
  function __construct(
    $project_root_dir, array $dependencies, Importer $importer,
    SystemUtilities $system_utilities, VDM $vdm
  ) {
    $this->project_root_dir = $project_root_dir;
    $this->dependencies = $dependencies;
    $this->importer = $importer;
    $this->system_utilities = $system_utilities;
    $this->vdm = $vdm;
  }
  
  function getDependencies() {
    return $this->dependencies;
  }
  
  function manageDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->system_utilities->out(
        "\n==========\nImporting: {$dependency->getName()}"
      );
      $this->importer->import($this->vdm->getVendorDir(), $dependency);
    }
  }
  
}
