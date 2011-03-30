<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Settings;

class SettingsTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $options = array(
    
    );
    $this->settings = new Settings($options);
  }
  
  function testDefaultSettings() {
    $settings = new Settings(array());
    $this->assertEquals('vendor', $settings->get('vendor_dir'));
  }
  
  function testSettingValues() {
    $settings = new Settings(array('vendor_dir' => 'lib/src'));
    $this->assertEquals('lib/src', $settings->get('vendor_dir'));
  }
  
  function testDefaultDataDirectory() {
    $settings = new Settings();
    $this->assertEquals(
      realpath(__DIR__ . '/../../../../data'),
      $settings->get('data_dir')
    );
  }

}
