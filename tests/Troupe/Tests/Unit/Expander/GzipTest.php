<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Gzip;
use \Troupe\Utilities;

class GzipTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->gzip_expander = new Gzip(new Utilities);
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testExpandingGzipFile() {
    $zip_path = $this->getFixturesDir() . '/foo.txt.gz';
    $this->gzip_expander->expand($zip_path, $this->getTestDataDir() . '/foo.txt');
    $this->assertFileExists($this->getExpectedTestFilePath('foo.txt'));
  }
  
  function testExpandingGzipFileReturnsListOfFilesExpanded() {
    $zip_path = $this->getFixturesDir() . '/foo.txt.gz';
    $return = $this->gzip_expander->expand($zip_path, $this->getTestDataDir() . '/foo.txt');
    $this->assertInternalType('array', $return);
    $this->assertContains($this->getExpectedTestFilePath('foo.txt'), $return);
  }
  
}
