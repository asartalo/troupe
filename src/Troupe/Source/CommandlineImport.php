<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

abstract class CommandlineImport extends AbstractSource {
  
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
    $output = $this->system_utilities->system(
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
  
}
