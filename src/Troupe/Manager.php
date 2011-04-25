<?php

namespace Troupe;

use \Troupe\VendorDirectory\Manager as VDM;

class Manager {
  
  private
    $project_root_dir,
    $dependencies,
    $importer,
    $output,
    $logger;
  
  // Refactor this
  function __construct(
    $project_root_dir, array $dependencies, Importer $importer,
    Output $output, VDM $vdm, Logger $logger
  ) {
    $this->project_root_dir = $project_root_dir;
    $this->dependencies = $dependencies;
    $this->importer = $importer;
    $this->output = $output;
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
  
  function importDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->output->out(
        "\n==========\nImporting: {$dependency->getName()}"
      );
      $this->logger->log(
        'import_results',
        $this->importer->import($this->vdm->getVendorDir(), $dependency)
      );
    }
  }
  
  function updateDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->output->out(
        "\n==========\nUpdating: {$dependency->getName()}"
      );
      $this->logger->log(
        'update_results',
        $this->importer->update($this->vdm->getVendorDir(), $dependency)
      );
    }
  }
  
  function outputDependencies() {
    foreach ($this->dependencies as $dependency) {
      $this->output->out($dependency);
    }
  }
  
}
