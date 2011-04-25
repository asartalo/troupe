<?php

namespace Troupe\Source;

use \Troupe\Executor;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Troupe\VendorDirectory\Manager as VDM;

/**
 * @todo Refactor this
 */
abstract class CommandlineImport implements Source {
  
  protected $executor, $vdm;
  
  function __construct($url, VDM $vdm, Executor $executor, $data_directory) {
    $this->executor = $executor;
    $this->url = $url;
    $this->data_directory = $data_directory;
    $this->vdm = $vdm;
  }
  
  function getDataDir() {
    return $this->data_directory . '/' . md5($this->url);
  }
  
  function getUrl() {
    return $this->url;
  }
  
  function import() {
    $troupe_lib_path = $this->getDataDir();
    if (!$this->vdm->isDataImported($this->url)) {
      return $this->checkOut($troupe_lib_path);
    }
    return new Success(
      \Troupe\Source\STATUS_OK, 
      "SUCCESS: {$this->url} has already been imported.",
      $troupe_lib_path
    );
  }
  
  function update() {
    if (!$this->vdm->isDataImported($this->url)) {
      return $this->import();
    }
    $troupe_lib_path = $this->getDataDir();
    $output = $this->executor->system(
      $this->getCliUpdateCommand($this->url, $troupe_lib_path)
    );
    if ($this->checkIfUpdateSuccess($output)) {
      return new Success(
        \Troupe\Source\STATUS_OK, 
        "SUCCESS: Updated {$this->url}.", $troupe_lib_path
      );
    }
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to update {$this->url}."
    );
  }
  
  private function checkOut($troupe_lib_path) {
    $output = $this->executor->system(
      $this->getCliCheckOutCommand($this->url, $troupe_lib_path)
    );
    if ($this->checkIfCheckoutSuccess($output)) {
      $this->vdm->importSuccess($this->url);
      return new Success(
        \Troupe\Source\STATUS_OK, 
        "SUCCESS: Imported {$this->url}.", $troupe_lib_path
      );
    }
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
  }
  
  abstract function getCliCheckOutCommand($url, $troupe_lib_path);
  
  abstract function checkIfCheckoutSuccess($last_line);
  
  abstract function getCliUpdateCommand($url, $troupe_lib_path);
  
  abstract function checkIfUpdateSuccess($last_line);
  
}
