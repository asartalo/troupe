<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Tar;

class TarTest extends \Troupe\Tests\TestCase {

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
    $this->assertFileExists($this->getTestFilePath('foo.txt'));
    $this->assertFileExists($this->getTestFilePath('bar.txt'));
  }
  
  function testExpandingTarFileReturnsListOfFilesExpanded() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file.tar';
    $return = $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingTarFileWithDirectories() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file2.tar';
    $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('tar_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getTestFilePath('tar_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getTestFilePath('tar_expander_test_file2/three'));
  }
  
  function testExpandingTarFileWithDirectoriesReturnsListOfFilesExpanded() {
    $tar_path = $this->getFixturesDir() .  '/tar_expander_test_file2.tar';
    $return = $this->tar_expander->expand($tar_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('tar_expander_test_file2'), $return);
    $this->assertEquals(1, count($return));
  }

}

