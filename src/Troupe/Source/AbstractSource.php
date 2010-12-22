<?php

namespace Troupe\Source;

use \Troupe\Source\Source;
use \Troupe\SystemUtilities;
use \Troupe\VendorDirectoryManager as VDM;

abstract class AbstractSource implements Source {
  
  protected $system_utilities, $url, $data_directory, $vdm;
  
  function __construct($url, VDM $vdm, SystemUtilities $system_utilities, $data_directory) {
    $this->system_utilities = $system_utilities;
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
  
}
