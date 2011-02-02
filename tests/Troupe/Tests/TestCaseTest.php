<?php

namespace Troupe\Tests;

require_once __DIR__ . '/TestCase.php';

class TestCaseTest extends TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../../data');
  }
  
  function tearDown() {
    $this->deleteDir($this->data_dir, false);
  }
  
  private function deleteDir($directory, $this_too = true) {
    if (file_exists($directory) && is_dir($directory)) {
      foreach (scandir($directory) as $value) {
        if ($value != "." && $value != "..") {
          $value = $directory . "/" . $value;
          if (is_dir($value)) {
            $this->deleteDir($value);
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
  
  function testGettingTestDataDir() {
    $this->assertEquals($this->data_dir , $this->getTestDataDir());
  }
  
  function testGettingFixturesDir() {
    $this->assertEquals(
      realpath(__DIR__ . '/../../fixtures'), $this->getFixturesDir()
    );
  }
  
  function testCreatingTestFile() {
    $this->createTestFile('foo.txt', 'foo bar');
    $this->assertFileExists($this->data_dir . '/foo.txt');
    $this->assertEquals(
      'foo bar', file_get_contents($this->data_dir . '/foo.txt')
    );
  }
  
  function testCreatingTestFileReturnsFilePath() {
    $this->assertEquals(
      $this->data_dir . '/foo.txt', $this->createTestFile('foo.txt', 'foo bar')
    );
  }
  
  function testClearingTestDirectory() {
    $this->createTestFile('foo.txt', 'Foo');
    $this->createTestFile('bar.txt', 'Bar');
    $this->clearTestDataDir();
    $this->assertFileNotExists($this->data_dir . '/foo.txt');
    $this->assertFileNotExists($this->data_dir . '/bar.txt');
  }
  
  function testGettingTestFilePath() {
    $this->createTestFile('foo.xml', '<foo></foo>');
    $this->assertEquals(
      $this->data_dir . '/foo.xml', $this->getTestFilePath('foo.xml')
    );
  }
  
  function testGettingTestFilePathForFileThatDoesNotExistReturnsEmptyStr() {
    $this->assertEquals('', $this->getTestFilePath('foo.html'));
  }
  
  function testGettingTestExpectedFilePath() {
    $this->assertEquals(
      $this->data_dir . '/foo.xml', $this->getExpectedTestFilePath('foo.xml')
    );
  }

}
