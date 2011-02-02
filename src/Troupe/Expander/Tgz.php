<?php

namespace Troupe\Expander;

use \Troupe\Expander\Gzip;
use \Troupe\Expander\PearArchiveTar;
use \Troupe\Utilities;

class Tgz implements Expander {
  
  private $utilities;
  
  function __construct(Utilities $utilities) {
    $this->utilities = $utilities;
  }
  
  function expand($archive, $to) {
    $gz_expander = new Gzip($this->utilities);
    $tar_expander = new Tar($this->utilities);
    //echo "\n========\n $archive -> $to";
    $gz_result = $gz_expander->expand($archive, $to . '.tar');
    rename($gz_result[0], $gz_result[0]);
    $tar_result = $tar_expander->expand($gz_result[0], $to);
    //echo "\n========\n {$gz_result[0]}_temp -> $to";
    unlink($gz_result[0]);
    unset($gz_expander);
    unset($tar_expander);
    return $tar_result;
  }
  
}
