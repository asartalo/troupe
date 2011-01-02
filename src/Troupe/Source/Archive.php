<?php

namespace Troupe\Source;

use \Troupe\Expander\Expander;
use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

// TODO: Refactor downloading to a separate class
// TODO: Or generalize as an Archive file and just get expander
class Archive extends AbstractSource {
  
  protected $expander, $system_utilities, $vdm;

  function __construct($url, VDM $vdm, SystemUtilities $system_utilities, $data_directory, Expander $expander) {
    $this->vdm = $vdm;
    $this->url = $url;
    $this->data_directory = $data_directory;
    $this->system_utilities = $system_utilities;
    $this->expander = $expander;
  }
  
  function import() {
    if (!$this->vdm->isDataImported($this->url)) {
      return $this->download();
    }
    return new Success(
      \Troupe\Source\STATUS_OK, 
      "SUCCESS: {$this->url} has already been imported.",
      $this->getDataDir()
    );
  }
  
  private function download() {
    $remote_file = $this->system_utilities->fopen($this->url, 'rb');
    if ($remote_file) {
      $local_file_path = $this->data_directory . '/' . 
        pathinfo($this->url, PATHINFO_BASENAME);
      $local_file = $this->system_utilities->fopen($local_file_path, 'wb');
      $this->system_utilities->fwrite(
        $local_file, $this->system_utilities->stream_get_contents($remote_file)
      );
      $this->expander->expand($local_file_path, $this->getDataDir());
      $this->system_utilities->fclose($local_file);
      $this->vdm->importSuccess($this->url);
      $status = new Success(
        \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
        $this->getDataDir()
      );
    } else {
      $status = new Failure(
        \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}. " .
        'There was a problem connecting to the remote resource.'
      );
    }
    $this->system_utilities->fclose($remote_file);
    return $status;
  }
  
}
