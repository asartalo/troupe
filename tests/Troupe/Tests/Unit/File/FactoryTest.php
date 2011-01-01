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
  
  
  /**
   * @dataProvider dataCreatingFiles
   */
  function testCreatingFiles($file, $file_contents, $file_class) {
    $file_path = $this->createTestFile($file, $file_contents);
    $this->assertEquals(
      new $file_class($file_path), $this->factory->getFile($file_path)
    );
  }
  
  function dataCreatingFiles() {
    return array(
      array('foo.php', 'A php file...', 'Troupe\File\Php'),
      array('foo.ini', '#A ini file...', 'Troupe\File\Ini'),
      array('foo.xpp', 'Some unknown file type.', 'Troupe\File\Common')
    );
  }
  
  function testReturnNonExistentFileForFileThatDoesNotExist() {
    $file_path = 'non-existent-file.php';
    $this->assertEquals(
      new NonExistentFile($file_path), $this->factory->getFile($file_path)
    );
  }
  
}
