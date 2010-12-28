<?php

namespace Troupe\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase {
  
  protected function quickMock($class, array $methods = array()) {
    return $this->getMock($class, $methods, array(), '', false);
  }
  
  protected function getTestDataDirectory() {
    return realpath(__DIR__ . '/../../data');
  }

}

