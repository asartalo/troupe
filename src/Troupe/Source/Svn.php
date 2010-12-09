<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class Svn extends AbstractSource {
  
  function import() {
    $troupe_lib_path = $this->getDataDir();
    if ($this->system_utilities->fileExists($troupe_lib_path)) {
      return $this->svnUpdate($troupe_lib_path);
    }
    return $this->svnCheckOut($troupe_lib_path);
  }
  
  private function svnUpdate($troupe_lib_path) {
    $output = $this->system_utilities->system(sprintf(
      'svn update %s', escapeshellarg($troupe_lib_path)
    ));
    if (strpos($output, 'At revision ') === 0) {
      return new Success(
        \Troupe\Source\STATUS_OK_UPDATE, 
        "SUCCESS: Updated {$this->url}.",
        $troupe_lib_path
      );
    }
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}."
    );
  }
  
  private function svnCheckOut($troupe_lib_path) {
    $output = $this->system_utilities->system(sprintf(
      'svn co %s %s', escapeshellarg($this->url), 
      escapeshellarg($troupe_lib_path)
    ));
    if (strpos($output, 'Checked out revision ') === 0) {
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
