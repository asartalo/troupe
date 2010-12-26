<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Logger;

class LoggerTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->logger = new Logger;
  }
  
  private function logTestData() {
    $this->logger->log('foo', 'bar');
    $this->logger->log('foo', 'baz');
    $this->logger->log('foo', 'far');
  }
  
  function testSettingLogData() {
    $this->logTestData();
    $this->assertEquals(array('bar', 'baz', 'far'), $this->logger->getLog('foo'));
  }
  
  function testGettingEmptyLogData() {
    $this->assertEquals(array(), $this->logger->getLog('foo'));
  }
  
  function testGettingLogDataAsString() {
    $this->logTestData();
    $this->assertEquals("bar\nbaz\nfar\n", $this->logger->getLogStr('foo'));
  }
  
  function testGettingStrForEmptyLogData() {
    $this->assertEquals('', $this->logger->getLogStr('foo'));
  }
  
  function testClearLog() {
    $this->logTestData();
    $this->logger->clearLog('foo');
    $this->assertEquals(array(), $this->logger->getLog('foo'));
  }

}
