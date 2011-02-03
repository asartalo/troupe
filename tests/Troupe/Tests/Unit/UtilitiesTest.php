<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Utilities;

class UtilitiesTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->utilities = new Utilities;
  }
  
  /**
   * @dataProvider dataGetFileExtension
   */
  function testGetFileExtension($filename, $expected_file_extension) {
    $this->assertEquals(
      $expected_file_extension,
      $this->utilities->getFileExtension($filename)
    );
  }
  
  function dataGetFileExtension() {
    return array(
      array('foo.html', 'html'),
      array('bar.xml',  'xml'),
      array('a/path/to/an/archive/file.zip', 'zip'),
      array('a/path/to/an/archive/baz.tar.gz', 'gz'),
    );
  }

}
