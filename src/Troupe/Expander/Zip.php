<?php

namespace Troupe\Expander;

require_once realpath(__DIR__ . '/../../PclZip/pclzip.lib.php');

class Zip implements Expander {
  
  function expand($archive, $to) {
    $archive = new \PclZip($archive);
    $files = $archive->extract($to);
    $return = array();
    foreach ($files as $file) {
      if (strpos(trim($file['stored_filename'], '/'), '/') > 0) {
        continue;
      }
      $return[] = rtrim($file['filename'], '/');
    }
    unset($archive);
    return $return;
  }
  
}
