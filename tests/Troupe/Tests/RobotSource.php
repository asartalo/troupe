<?php

namespace Troupe\Tests;
use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class RobotSource implements \Troupe\Source\Source {
  
  private static
    $instances = array(),
    $import_status = array(),
    $status_msgs = array(
      'import_success' => "SUCCESS: Robot says '%s' import is successful.",
      'import_failure' => "FAIL: Robot says '%s' import failed.",
      'update_success' => "SUCCESS: Robot says '%s' update is successful.",
      'update_failure' => "FAIL: Robot says '%s' update failed.",
    );
    
  private $url;
  
  static function getInstance($url) {
    if (!isset(self::$instances[$url])) {
      self::$instances[$url] = new self($url);
    }
    return self::$instances[$url];
  }
  
  private function __construct($url) {
    $this->url = $url;
    self::createFailureStatus($url, 'import_failure');
  }
  
  static function setImportSuccessStatus($url) {
    self::createSuccessStatus($url, 'import_success');
  }
  
  static function setImportFailureStatus($url) {
    self::createFailureStatus($url, 'import_failure');
    self::removeDataDir($url);
  }
  
  static function setUpdateSuccessStatus($url) {
    self::createSuccessStatus($url, 'update_success');
  }
  
  static function setUpdateFailureStatus($url) {
    self::createFailureStatus($url, 'update_failure');
    self::removeDataDir($url);
  }
  
  private static function createSuccessStatus($url, $msg_template) {
    self::createStatus(
      'Success', \Troupe\Source\STATUS_OK, $url, $msg_template
    );
    mkdir(self::getInstance($url)->getDataDir());
  }
  
  private static function createFailureStatus($url, $msg_template) {
    self::createStatus(
      'Failure', \Troupe\Source\STATUS_FAIL, $url, $msg_template
    );
  }
  
  private static function createStatus($type, $status_code, $url, $msg_template) {
    $type = "Troupe\Status\\$type";
    self::$import_status[$url] = new $type(
      $status_code, sprintf(self::$status_msgs[$msg_template], $url)
    );
  }
  
  private static function removeDataDir($url) {
    if (file_exists($data_dir = self::getInstance($url)->getDataDir())) {
      rmdir($data_dir);
    }
  }
  
  function import() {
    return self::$import_status[$this->url];
  }
  
  function update() {
    return self::$import_status[$this->url];
  }
  
  function getDataDir() {
    return realpath(__DIR__ . '/../../data') . '/' . md5($this->url);
  }
  
  function getUrl() {
    return $this->url;
  }
  
  

}
