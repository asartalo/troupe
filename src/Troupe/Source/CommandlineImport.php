<?php

namespace Troupe\Source;

use \Troupe\Executor;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Troupe\VendorDirectoryManager as VDM;

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
  
  private function checkOut($troupe_lib_path) {
    $output = $this->executor->system(
      $this->getCliCommand($this->url, $troupe_lib_path)
    );
    if ($this->getCheckIfSuccess($output)) {
      $this->vdm->importSuccess($this->url);
      return new Success(
        \Troupe\Source\STATUS_OK, 
        "SUCCESS: Imported {$this->url}.",
        $troupe_lib_path
      );
    }
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
  }
  
  abstract function getCliCommand($url, $troupe_lib_path);
  
  abstract function getCheckIfSuccess($last_line);
  
}
