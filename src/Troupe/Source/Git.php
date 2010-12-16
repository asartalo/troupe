<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class Git extends AbstractSource {
  
  function import() {
    $troupe_lib_path = $this->getDataDir();
    return $this->gitCheckOut($troupe_lib_path);
  }
  
  private function gitCheckOut($troupe_lib_path) {
    $output = $this->system_utilities->system(sprintf(
      'git clone %s %s', escapeshellarg($this->url), 
      escapeshellarg($troupe_lib_path)
    ));
    if (strpos($output, 'Initialized empty Git repository in') === 0) {
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
