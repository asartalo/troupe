<?php

namespace Troupe\Expander;

require_once realpath(__DIR__ . '/../../iUnTar/iuntar.php');

class Tar implements Expander {
  
  function expand($archive, $to) {
    $first_scandir = scandir($to);
    if (file_exists($archive) && untar($archive, $to)) {
      $second_scandir = scandir($to);
      $diff = array_diff($second_scandir, $first_scandir);
      $result = array();
      foreach ($diff as $item) {
        $result[] = $to . '/' . $item;
      }
      return $result;
    }
    return array();
  }
  
}
