<?php

namespace Troupe\Expander;

use \Troupe\Expander\Gzip;
use \Troupe\Expander\Tar;

class Tgz implements Expander {
  
  function expand($archive, $to) {
    $gz_expander = new Gzip;
    $tar_expander = new Tar;
    $gz_result = $gz_expander->expand($archive, $to);
    $tar_result = $tar_expander->expand($gz_result[0], $to);
    unlink($gz_result[0]);
    unset($gz_expander);
    unset($tar_expander);
    return $tar_result;
  }
  
}
