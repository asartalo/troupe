<?php

namespace Troupe\Tests;

require_once realpath(__DIR__ . '/../../bootstrap.php');

class TestCaseTest extends TestCase {
  
  function setUp() {
    $this->data_dir = realpath(__DIR__ . '/../..') . '/data';
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
  
  function testCreateTestDataDirIfItDoesNotExistYet() {
    $this->deleteDir($this->data_dir);
    $this->getTestDataDir();
    $this->assertFileExists($this->data_dir);
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
  
  function testCreatingTestDirectory() {
    $this->createTestDir('foo');
    $this->assertFileExists($dir = $this->data_dir . '/foo');
    $this->assertTrue(is_dir($dir));
  }
  
  function testClearingTestDirectory() {
    $this->createTestFile('foo.txt', 'Foo');
    $this->createTestFile('bar.txt', 'Bar');
    $this->createTestDir('foo');
    $this->createTestFile('foo/bar.txt', 'Bar2');
    $this->clearTestDataDir();
    $this->assertFileNotExists($this->data_dir . '/foo.txt');
    $this->assertFileNotExists($this->data_dir . '/bar.txt');
    $this->assertFileNotExists($this->data_dir . '/foo/bar.txt');
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
