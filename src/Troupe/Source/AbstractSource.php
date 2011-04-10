<?php

namespace Troupe\Source;

use \Troupe\Source\Source;
use \Troupe\SystemUtilities;
use \Troupe\VendorDirectory\Manager as VDM;

abstract class AbstractSource implements Source {
  
  protected $url, $data_directory;
  
  function getDataDir() {
    return $this->data_directory . '/' . md5($this->url);
  }
  
  function getUrl() {
    return $this->url;
  }
  
}
