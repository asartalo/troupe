<?php

namespace Troupe\Expander;

require_once realpath(__DIR__ . '/../../PclZip/pclzip.lib.php');

class Zip implements Expander {
  
  private $pclzip;
  
  function expand($archive, $to) {
    $archive = new \PclZip($archive);
    $files = $archive->extract($to);
    $return = array();
    foreach ($files as $file) {
      $return[] = $file['filename'];
    }
    unset($archive);
    return $return;
  }
  
}
