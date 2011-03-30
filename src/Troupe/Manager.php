<?php

namespace Troupe;

use \Troupe\VendorDirectoryManager as VDM;

class Manager {
  
  private
    $project_root_dir,
    $dependencies,
    $importer,
    $system_utilities,
    $logger;
  
  // Refactor this
  function __construct(
    $project_root_dir, array $dependencies, Importer $importer,
    SystemUtilities $system_utilities, VDM $vdm, Logger $logger
  ) {
    $this->project_root_dir = $project_root_dir;
    $this->dependencies = $dependencies;
    $this->importer = $importer;
    $this->system_utilities = $system_utilities;
    $this->vdm = $vdm;
    $this->logger = $logger;
  }
  
  function getDependencies() {
    return $this->dependencies;
  }
  
  // TODO: This is probably useless
  function getVendorDirectory() {
    return $this->project_root_dir . '/' . $this->vdm->getVendorDir();
  }
  
  // TODO: rename to importDependencies
  function importDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->system_utilities->out(
        "\n==========\nImporting: {$dependency->getName()}"
      );
      $this->logger->log(
        'import_results',
        $this->importer->import($this->vdm->getVendorDir(), $dependency)
      );
    }
  }
  
}
