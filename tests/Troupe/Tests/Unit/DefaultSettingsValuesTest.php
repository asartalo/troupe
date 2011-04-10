<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\DefaultSettingsValues;

class DefaultSettingsValuesTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $defaults = new DefaultSettingsValues('/foo/bar');
    $this->defaults = $defaults->getValues();
  }
  
  /**
   * @dataProvider dataDefaults
   */
  function testDefaults($key, $expected) {
    $this->assertEquals(
      $expected, $this->defaults[$key]
    );
  }
  
  function dataDefaults() {
    $user_info = posix_getpwuid(posix_getuid());
    return array(
      array('data_dir', $user_info['dir'] . '/.troupe/data'),
      array('vendor_dir', 'vendor'),
      array('project_dir', '/foo/bar'),
      array('test_dir', realpath(__DIR__ . '/../../../'))
    );
  }

}

