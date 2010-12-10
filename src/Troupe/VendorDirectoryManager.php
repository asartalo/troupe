<?php

namespace Troupe;

class VendorDirectoryManager {
  
  private $utilities;
  
  function __construct(SystemUtilities $utilities) {
    $this->utilities = $utilities;
  }
  
  function link($vendor_link, $orig_data_path) {
    if ($this->utilities->fileExists($vendor_link)) {
      if ($this->utilities->readLink($vendor_link) != $orig_data_path) {
        $this->utilities->unlink($vendor_link);
        $this->utilities->symlink($orig_data_path, $vendor_link);
      }
    } else {
      $this->utilities->symlink($orig_data_path, $vendor_link);
    }
  }
  
}
