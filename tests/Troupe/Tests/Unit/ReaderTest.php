<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase {

  function setUp() {
    $this->system_utilities = $this->getMock('Troupe\SystemUtilities');
    $this->project_directory = 'a/directory';
    $this->reader = new Reader(
      $this->project_directory, $this->system_utilities
    );
  }
  
  function testReadChecksIfAssemblyFileExistsInProjectDirectory() {
    $this->system_utilities->expects($this->once())
      ->method('fileExists')
      ->with('a/directory/mytroupe.php');
    $this->reader->read();
  }
  
  function testReadIncludesAndReturnsValueOfAssemblyFile() {
    $foo = array(1, 2, 3);
    $this->system_utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(true));
    $this->system_utilities->expects($this->once())
      ->method('includeFile')
      ->with('a/directory/mytroupe.php')
      ->will($this->returnValue($foo));
    $this->assertEquals($foo, $this->reader->read());
  }
  
  function testReadReturnsEmptyArrayWhenAssemblyFileIsNotFound() {
    $this->system_utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(false));
    $this->assertEquals(array(), $this->reader->read());
  }
  
  function testReadSkipsIncludesIfAssemblyFileIsNotFound() {
    $this->system_utilities->expects($this->once())
      ->method('fileExists')
      ->will($this->returnValue(false));
    $this->system_utilities->expects($this->never())
      ->method('includeFile');
    $this->reader->read();
  }
  
}
