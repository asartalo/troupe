<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Zip;

class ZipTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->zip_expander = new Zip;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingZipFile() {
    $zip_path = $this->getFixturesDir() .  '/zip_expander_test_file.zip';
    $this->zip_expander->expand($zip_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('foo.txt'));
    $this->assertFileExists($this->getTestFilePath('bar.txt'));
  }
  
  function testExpandingZipFileReturnsListOfFilesExpanded() {
    $zip_path = $this->getFixturesDir() .  '/zip_expander_test_file.zip';
    $return = $this->zip_expander->expand($zip_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('foo.txt'), $return);
    $this->assertContains($this->getTestFilePath('bar.txt'), $return);
  }
  
  function testExpandingZipFileWithDirectories() {
    $zip_path = $this->getFixturesDir() .  '/zip_expander_test_file2.zip';
    $this->zip_expander->expand($zip_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('zip_expander_test_file2/one.txt'));
    $this->assertFileExists($this->getTestFilePath('zip_expander_test_file2/two.txt'));
    $this->assertFileExists($this->getTestFilePath('zip_expander_test_file2/three'));
  }

}

