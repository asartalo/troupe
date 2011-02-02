<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Tar;

class PearArchiveTarTest extends \Troupe\Tests\TestCase {

  private static $pear_archive_tar_lib_exists = null;

  function setUp() {
    $this->clearTestDataDir();
    $this->tar_expander = new Tar;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingTarFile() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file.tar';
    $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('foo.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('bar.txt'));
  }
  
  function testExpandingTarFileReturnsListOfFilesExpanded() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file.tar';
    $return = $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getExpectedTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingTarFileWithDirectories() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file2.tar';
    $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('tar_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tar_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tar_expander_test_file2/three'));
  }
  
  function testExpandingTarFileWithDirectoriesReturnsListOfFilesExpanded() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file2.tar';
    $return = $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('tar_expander_test_file2'), $return);
    $this->assertEquals(1, count($return));
  }
  
  function testExpandingTarFileWithDirectories2() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file3.tar';
    $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('foo/one.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('foo/two.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('foo/three'));
    $this->assertFileExists($this->getExpectedTestFilePath('foo/three/three.txt'));
  }
  
  function testExpandingTarFileWithDirectoriesButNoDirectoryEntriesOnTarFile() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file4.tar';
    $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('numbers/1.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('numbers/2.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('numbers/3.txt'));
  }
  
  function testAutoCreateDirectories() {
    $directory = 'foo/bar/baz/be';
    $this->tar_expander->autoCreateDirectories($this->getTestDataDir(), $directory);
    $this->assertFileExists($this->getTestDataDir() . '/' . $directory);
  }
  
  function testAutoCreateDirectoriesHonorsLimits() {
    $directory = 'foo/bar/baz/be';
    $this->tar_expander->autoCreateDirectories($this->getTestDataDir() . '/boo', $directory);
    $this->assertFileNotExists($this->getTestDataDir() . '/boo/' . $directory);
  }

}

