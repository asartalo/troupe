<?php

namespace Troupe;

use \Troupe\Settings;
use \Troupe\SystemUtilities;
use \Troupe\DataStore;

class VendorDirectoryManager {
  
  private $utilities, $data_store, $settings;
  
  function __construct(SystemUtilities $utilities, DataStore $data_store, Settings $settings) {
    $this->utilities = $utilities;
    $this->data_store = $data_store;
    $this->settings = $settings;
  }
  
  function link($vendor_link, $orig_data_path) {
    if ($this->fileExists($vendor_link)) {
      if ($this->utilities->readLink($vendor_link) != $orig_data_path) {
        $this->utilities->unlink($vendor_link);
        $this->symlink($orig_data_path, $vendor_link);
      }
    } else {
      $this->symlink($orig_data_path, $vendor_link);
    }
  }
  
  private function fileExists($file) {
    return $this->utilities->fileExists($file);
  }
  
  private function symlink($target, $link) {
    $this->utilities->symlink($target, $link);
  }
  
  // TODO: Stop checking if file exists when it already does
  function getVendorDir() {
    $vendor_dir = $this->settings->get('vendor_dir');
    if (!$this->fileExists($vendor_dir)) {
      $this->utilities->mkdir($vendor_dir, 0755, true);
    }
    return $vendor_dir;
  }
  
  function importSuccess($url) {
    $this->data_store->set('data_import', $url, true);
  }
  
  function isDataImported($url) {
    return (bool) $this->data_store->get('data_import', $url);
  }
  
}
