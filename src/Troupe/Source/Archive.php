<?php

namespace Troupe\Source;

use \Troupe\Expander\Expander;
use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Cibo;

// TODO: Refactor downloading to a separate class
// TODO: Or generalize as an Archive file and just get expander
class Archive extends AbstractSource {
  
  protected $expander, $system_utilities, $vdm, $cibo;

  function __construct(
    $url, VDM $vdm, SystemUtilities $system_utilities, $data_directory,
    Expander $expander, Cibo $cibo
  ) {
    $this->vdm = $vdm;
    $this->url = $url;
    $this->data_directory = $data_directory;
    $this->system_utilities = $system_utilities;
    $this->expander = $expander;
    $this->cibo = $cibo;
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
    if ($this->cibo->download($this->url, $this->getLocalFilePath())) {
      $this->vdm->importSuccess($this->url);
      $this->expander->expand($this->getLocalFilePath(), $this->getDataDir());
      return new Success(
        \Troupe\Source\STATUS_OK, "SUCCESS: Imported {$this->url}.",
        $this->getDataDir()
      );
    }
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}. " .
      'There was a problem downloading the remote resource.'
    );
  }
  
  private function getLocalFilePath() {
    return $this->data_directory . '/' . 
        pathinfo($this->url, PATHINFO_BASENAME);
  }
  
}
