<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Gzip;

class GzipTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->gzip_expander = new Gzip;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingGzipFile() {
    $zip_path = $this->getFixturesDir() . '/foo.txt.gz';
    $this->gzip_expander->expand($zip_path, $this->getTestDataDir());
    $this->assertFileExists($this->getTestFilePath('foo.txt'));
  }
  
  function testExpandingGzipFileReturnsListOfFilesExpanded() {
    $zip_path = $this->getFixturesDir() . '/foo.txt.gz';
    $return = $this->gzip_expander->expand($zip_path, $this->getTestDataDir());
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getTestFilePath('foo.txt'), $return);
  }
  
}
