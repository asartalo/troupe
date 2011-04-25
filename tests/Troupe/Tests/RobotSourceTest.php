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
  
  function testGettingUrl() {
    $this->assertEquals($this->url, $this->source->getUrl());
  }
  
  function testGetDataDirReturnsPathToTestsDataPlusMd5edUrl() {
    $this->assertEquals(
      $this->getTestDataDir() . '/' . md5($this->url),
      $this->source->getDataDir()
    );
  }
  
  function testRobotSettingImportSuccessStatus() {
    RobotSource::setImportSuccessStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Success', $this->source->import()
    );
  }
  
  function testRobotImportSuccessStatusHasHelpfulMessage() {
    RobotSource::setImportSuccessStatus($this->url);
    $this->assertEquals(
      "SUCCESS: Robot says '{$this->url}' import is successful.",
      $this->source->import()->getMessage()
    );
  }
  
  function testRobotSettingImportFailureStatus() {
    RobotSource::setImportFailureStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->import()
    );
  }
  
  function testRobotImportFailureStatusHasHelpfulMessage() {
    RobotSource::setImportFailureStatus($this->url);
    $this->assertEquals(
      "FAIL: Robot says '{$this->url}' import failed.",
      $this->source->import()->getMessage()
    );
  }
  
  function testInstanceSetsImportFailureStatusByDefault() {
    $this->source = RobotSource::getInstance('someurl');
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->import()
    );
  }
  
  function testSettingImportSuccessStatusCreatesDataDir() {
    RobotSource::setImportSuccessStatus($this->url);
    $this->assertFileExists($this->source->getDataDir());
  }
  
  function testSettingImportFailureStatusRemovesDataDir() {
    RobotSource::setImportSuccessStatus($this->url);
    RobotSource::setImportFailureStatus($this->url);
    $this->assertFileNotExists($this->source->getDataDir());
  }
  
  /*** Update **/
  function testRobotSettingUpdateSuccessStatus() {
    RobotSource::setUpdateSuccessStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Success', $this->source->update()
    );
  }
  
  function testRobotUpdateSuccessStatusHasHelpfulMessage() {
    RobotSource::setUpdateSuccessStatus($this->url);
    $this->assertEquals(
      "SUCCESS: Robot says '{$this->url}' update is successful.",
      $this->source->update()->getMessage()
    );
  }
  
  function testRobotSettingUpdateFailureStatus() {
    RobotSource::setUpdateFailureStatus($this->url);
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->update()
    );
  }
  
  function testRobotUpdateFailureStatusHasHelpfulMessage() {
    RobotSource::setUpdateFailureStatus($this->url);
    $this->assertEquals(
      "FAIL: Robot says '{$this->url}' update failed.",
      $this->source->update()->getMessage()
    );
  }
  
  function testInstanceSetsUpdateFailureStatusByDefault() {
    $this->source = RobotSource::getInstance('someurl');
    $this->assertInstanceOf(
      'Troupe\Status\Failure', $this->source->update()
    );
  }
  
  function testSettingUpdateSuccessStatusCreatesDataDir() {
    RobotSource::setUpdateSuccessStatus($this->url);
    $this->assertFileExists($this->source->getDataDir());
  }
  
  function testSettingUpdateFailureStatusRemovesDataDir() {
    RobotSource::setUpdateSuccessStatus($this->url);
    RobotSource::setUpdateFailureStatus($this->url);
    $this->assertFileNotExists($this->source->getDataDir());
  }

}
