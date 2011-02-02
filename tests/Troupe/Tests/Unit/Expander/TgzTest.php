<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Tgz;
use \Troupe\Utilities;

class TgzTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->tgz_expander = new Tgz(new Utilities);
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingTgzFile() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tar.gz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('foo.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('bar.txt'));
  }
  
  function testExpandingTgzFileReturnsListOfFilesExpanded() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tar.gz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getExpectedTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingTgzFileWithDirectories() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tar.gz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/three'));
  }
  
  function testExpandingTgzFileWithDirectoriesReturnsListOfFilesExpanded() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tar.gz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('tgz_expander_test_file2'), $return);
    $this->assertEquals(1, count($return));
  }
  
  function testExpandingTgzFile2() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tgz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('foo.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('bar.txt'));
  }
  
  function testExpandingTgzFileReturnsListOfFilesExpanded2() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tgz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getExpectedTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingTgzFileWithDirectories2() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tgz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getExpectedTestFilePath('tgz_expander_test_file2/three'));
  }
  
  function testExpandingTgzFileWithDirectoriesReturnsListOfFilesExpanded2() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tgz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('tgz_expander_test_file2'), $return);
    $this->assertEquals(1, count($return));
  }

}

