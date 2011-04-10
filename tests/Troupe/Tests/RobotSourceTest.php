<?php

namespace Troupe\Tests;

require_once realpath(__DIR__ . '/../../bootstrap.php');
require_once realpath(__DIR__ . '/RobotSource.php');

use \Troupe\Status\Success;
use \Troupe\Status\Failure;

class RobotSourceTest extends TestCase {

  function setUp() {
    $this->url = 'http://foo.com';
    $this->source = RobotSource::getInstance($this->url);
    $this->clearTestDataDir();
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testInstanceReturnsSameInstanceForSameObjectPassed() {
    $this->assertSame(
      RobotSource::getInstance($this->url), $this->source
    );
  }
  
  function testInstanceReturnsAnInstanceOfRobotSource() {
    $this->assertInstanceOf('Troupe\Tests\RobotSource', $this->source);
  }
  
  function testInstanceReturnsADifferentInstantForSomeOtherUrlIsPassed() {
    $this->assertNotSame(
      RobotSource::getInstance('http://bar.com'), $this->source
    );
  }
  
  function testRobotSettingSuccessStatus() {
    RobotSource::setSuccessStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Success', $this->source->import()
    );
  }
  
  function testRobotSuccessStatusHasHelpfulMessage() {
    RobotSource::setSuccessStatus($this->url);
    $this->assertEquals(
      "SUCCESS: Robot says '{$this->url}' import is successful.",
      $this->source->import()->getMessage()
    );
  }
  
  function testRobotSettingFailureStatus() {
    RobotSource::setFailureStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->import()
    );
  }
  
  function testRobotFailureStatusHasHelpfulMessage() {
    RobotSource::setFailureStatus($this->url);
    $this->assertEquals(
      "FAIL: Robot says '{$this->url}' import failed.",
      $this->source->import()->getMessage()
    );
  }
  
  function testInstanceSetsFailureStatusByDefault() {
    $this->source = RobotSource::getInstance('someurl');
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->import()
    );
  }
  
  function testGettingUrl() {
    $this->assertEquals($this->url, $this->source->getUrl());
  }
  
  function testGetDataDirReturnsPathToTestsDataPlusMd5edUrl() {
    $this->assertEquals(
      $this->getTestDataDir() . '/' . md5($this->url),
      $this->source->getDataDir()
    );
  }
  
  function testSettingSuccessStatusCreatesDataDir() {
    RobotSource::setSuccessStatus($this->url);
    $this->assertFileExists($this->source->getDataDir());
  }
  
  function testSettingFailureStatusRemovesDataDir() {
    RobotSource::setSuccessStatus($this->url);
    RobotSource::setFailureStatus($this->url);
    $this->assertFileNotExists($this->source->getDataDir());
  }

}
