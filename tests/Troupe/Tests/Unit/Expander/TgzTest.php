<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Tgz;

class TgzTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->tgz_expander = new Tgz;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingTgzFile() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tar.gz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('foo.txt'));
    $this->assertFileExists($this->getTestFilePath('bar.txt'));
  }
  
  function testExpandingTgzFileReturnsListOfFilesExpanded() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file.tar.gz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingTgzFileWithDirectories() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tar.gz';
    $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('tgz_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getTestFilePath('tgz_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getTestFilePath('tgz_expander_test_file2/three'));
  }
  
  function testExpandingTgzFileWithDirectoriesReturnsListOfFilesExpanded() {
    $tgz_path = $this->getFixturesDir() .  '/tgz_expander_test_file2.tar.gz';
    $return = $this->tgz_expander->expand($tgz_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('tgz_expander_test_file2'), $return);
    $this->assertEquals(1, count($return));
  }

}

