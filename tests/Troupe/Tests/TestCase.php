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
  
  private function recursiveDelete($directory, $this_too = true) {
    if (file_exists($directory) && is_dir($directory)) {
      foreach (scandir($directory) as $value) {
        if ($value != "." && $value != "..") {
          $value = $directory . "/" . $value;
          if (is_dir($value)) {
            $this->recursiveDelete($value);
          } elseif (is_file($value)) {
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
    return realpath(__DIR__ . '/../../data');
  }
  
  protected function getTestFilePath($file) {
    $file_path = $this->getTestDataDir() . "/$file";
    return file_exists($file_path) ? $file_path : '';
  }

}

