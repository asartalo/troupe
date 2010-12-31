<?php
namespace Troupe\Tests\Unit\File;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\File\Common;

class CommonTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  private function createTestSetup() {
    $this->createTestFile('foo.txt', 'Foo.');
    $this->file = new Common($this->getTestFilePath('foo.txt'));
  }
  
  function testGettingFileContents() {
    $this->createTestSetup();
    $this->assertEquals('Foo.', $this->file->getContents());
  }
  
  function testGettingFilePath() {
    $this->createTestSetup();
    $this->assertEquals(
      $this->getTestFilePath('foo.txt'), $this->file->getPath()
    );
  }
  
  function testFileExists() {
    $this->createTestSetup();
    $this->assertEquals(true, $this->file->isFileExists());
  }
  
  function testThrowErrorOnInstantiationWhenFileDoesNotExist() {
    $this->setExpectedException(
      'Troupe\File\Exception', 
      "Unable to instantiate. The file 'foo' does not exist."
    );
    new Common('foo');
  }

}
