<?php

namespace Troupe\Tests;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class RobotSource implements \Troupe\Source\Source {
  
  private static
    $instances = array(),
    $import_status = array();
  private $url;
  
  static function getInstance($url) {
    if (!isset(self::$instances[$url])) {
      self::$instances[$url] = new self($url);
    }
    return self::$instances[$url];
  }
  
  private function __construct($url) {
    $this->url = $url;
    self::createFailureStatus($url);
  }
  
  static function setSuccessStatus($url) {
    self::$import_status[$url] = new Success(
      \Troupe\Source\STATUS_OK,
      "SUCCESS: Robot says '$url' import is successful."
    );
    mkdir(self::getInstance($url)->getDataDir());
  }
  
  static function setFailureStatus($url) {
    self::createFailureStatus($url);
    if (file_exists($data_dir = self::getInstance($url)->getDataDir())) {
      rmdir($data_dir);
    }
  }
  
  static function createFailureStatus($url) {
    self::$import_status[$url] = new Failure(
      \Troupe\Source\STATUS_FAIL,
      "FAIL: Robot says '$url' import failed."
    );
  }
  
  function import() {
    return self::$import_status[$this->url];
  }
  
  function getDataDir() {
    return realpath(__DIR__ . '/../../data') . '/' . md5($this->url);
  }
  
  function getUrl() {
    return $this->url;
  }
  
  

}
