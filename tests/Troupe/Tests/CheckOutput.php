<?php

namespace Troupe\Tests;

class CheckOutput extends \Troupe\Output {

  private $output = '';

  function out($string) {
    $this->output .= "$string\n";
  }
  
  function getOutput() {
    return $this->output;
  }
  
}
