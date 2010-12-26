<?php

namespace Troupe;

use \Troupe\Settings;

class Logger {
  
  private $ldata = array();
  
  function log($namespace, $data) {
    if (!isset($this->ldata[$namespace])) {
      $this->ldata[$namespace] = array();
    }
    $this->ldata[$namespace][] = $data;
  }
  
  function getLog($namespace) {
    if (isset($this->ldata[$namespace])) {
      return $this->ldata[$namespace];
    }
    return array();
  }
  
  function getLogStr($namespace) {
    if ($this->getLog($namespace)) {
      return implode("\n", $this->getLog($namespace)) . "\n";
    }
    return '';
  }
  
  function clearLog($namespace) {
    unset($this->ldata[$namespace]);
  }
  
}
