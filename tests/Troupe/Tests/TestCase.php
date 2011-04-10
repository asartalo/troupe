<?php

namespace Troupe\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase {
  
  protected function quickMock($class, array $methods = array()) {
    return $this->getMock($class, $methods, array(), '', false);
  }
  
  protected function createTestFile($path, $contents) {
    $full_path = $this->getTestDataDir() . '/' . $path;
    $file = fopen($full_path, 'wb');
    fwrite($file, $contents);
    fclose($file);
    return $full_path;
  }
  
  protected function createTestDir($path) {
    mkdir($this->getTestDataDir() . "/$path");
  }
  
  private function recursiveDelete($directory, $this_too = true) {
    if (file_exists($directory) && is_dir($directory)) {
      foreach (scandir($directory) as $value) {
        if ($value != "." && $value != "..") {
          $value = $directory . "/" . $value;
          if (is_dir($value)) {
            $this->recursiveDelete($value);
          } else {
            @unlink($value);
          }
        }
      }
      if ($this_too) {
        return rmdir($directory);
      }
    } else {
       return false;
    }
  }
  
  protected function clearTestDataDir() {
    $this->recursiveDelete($this->getTestDataDir(), false);
  }
  
  protected function getTestDataDir() {
    $data_dir = realpath(__DIR__ . '/../../') . '/data';
    if (!file_exists($data_dir)) {
      mkdir($data_dir);
    }
    return $data_dir;
  }
  
  protected function getFixturesDir() {
    return realpath(__DIR__ . '/../../fixtures');
  }
  
  protected function getTestFilePath($file) {
    $file_path = $this->getExpectedTestFilePath($file);
    return file_exists($file_path) ? $file_path : '';
  }
  
  protected function getExpectedTestFilePath($file) {
    return $this->getTestDataDir() . "/$file";
  }

}

