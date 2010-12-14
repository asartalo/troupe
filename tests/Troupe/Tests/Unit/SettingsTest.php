<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Settings;

class SettingsTest extends \PHPUnit_Framework_TestCase {

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

}
