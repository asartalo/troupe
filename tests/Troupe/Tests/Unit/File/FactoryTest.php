<?php
namespace Troupe\Tests\Unit\File;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\File\Factory;
use \Troupe\File\Php;
use \Troupe\File\Ini;
use \Troupe\File\Common;
use \Troupe\File\NonExistentFile;

class FactoryTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->clearTestDataDir();
    $this->factory = new Factory;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testCreatingPhpFile() {
    $file_path = $this->createTestFile('foo.php', 'A php file...');
    $this->assertEquals(
      new Php($file_path), $this->factory->getFile($file_path)
    );
  }
  
  function testCreatingIniFile() {
    $file_path = $this->createTestFile('foo.ini', '#An ini file...');
    $this->assertEquals(
      new Ini($file_path), $this->factory->getFile($file_path)
    );
  }
  
  function testCreatingUnknownFile() {
    $file_path = $this->createTestFile('foo.xpp', 'Some unknown file type.');
    $this->assertEquals(
      new Common($file_path), $this->factory->getFile($file_path)
    );
  }
  
  function testReturnNonExistentFileForFileThatDoesNotExist() {
    $file_path = 'non-existent-file.php';
    $this->assertEquals(
      new NonExistentFile($file_path), $this->factory->getFile($file_path)
    );
  }
  
}
