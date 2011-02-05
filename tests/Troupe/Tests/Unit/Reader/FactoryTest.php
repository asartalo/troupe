<?php
namespace Troupe\Tests\Unit\Reader;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Reader\Factory;

class FactoryTest extends \Troupe\Tests\TestCase {
  
  function setUp() {
    $this->system_utilities = $this->quickMock('Troupe\SystemUtilities');
    $this->file = $this->quickMock('Troupe\File\File');
    $this->factory = new Factory($this->system_utilities);
  }
  
  /**
   * @dataProvider dataCreatingReader
   */
  function testCreatingReader($type, $reader_class) {
    $this->file->expects($this->any())
      ->method('getType')
      ->will($this->returnValue($type));
    $full_file_class = "Troupe\\Reader\\$reader_class";
    $this->assertEquals(
      new $full_file_class($this->file, $this->system_utilities),
      $this->factory->getReader($this->file)
    );
  }
  
  function dataCreatingReader() {
    return array(
      array('php', 'Php'),
      array('ini', 'Ini'),
      array('', 'Unknown')
    );
  }
  
}
