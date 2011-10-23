<?php

namespace Troupe\Source;

use \Troupe\SystemUtilities;
use \Troupe\VendorDirectory\Manager as VDM;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;
use \Cibo\Cibo;

/**
 * @todo Refactor downloading to a separate class
 *       or generalize as an Archive file and just get expander
 * @todo see if it is worthwile to create a separate logic for update()
 *       instead of just using import()
 */
class File extends AbstractSource {

  protected $system_utilities, $vdm, $cibo;

  function __construct(
    $url, VDM $vdm, SystemUtilities $system_utilities, $data_directory,
    Cibo $cibo
  ) {
    $this->vdm = $vdm;
    $this->url = $url;
    $this->data_directory = $data_directory;
    $this->system_utilities = $system_utilities;
    $this->cibo = $cibo;
  }

  function import() {
    if (!$this->vdm->isDataImported($this->url)) {
      $result = $this->cibo->download(
        $this->url, $this->getLocalFilePath()
      );
      if ($result) {
        $this->vdm->importSuccess($this->url);
        $this->system_utilities->rename(
          $this->getLocalFilePath(), $this->getDataDir()
        );
        return new Success(
          \Troupe\Source\STATUS_OK,
          "SUCCESS: Imported {$this->url}.",
          $this->getDataDir()
        );
      } else {
        return new Failure(
          \Troupe\Source\STATUS_FAIL, "FAIL: Unable to import {$this->url}. " .
          'There was a problem downloading the remote resource.'
        );
      }
    }
    return new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: {$this->url} has already been imported.",
      $this->getDataDir()
    );
  }

  function update() {
    return $this->import();
  }

  private function getLocalFilePath() {
    return $this->data_directory . '/' .
        pathinfo($this->url, PATHINFO_BASENAME);
  }

}
