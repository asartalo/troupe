<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\Settings;
use \Troupe\Tests\TestCase;

class SettingsTest extends TestCase {

  function setUp() {
    $this->settings = new Settings;
  }
  
  /**
   * @dataProvider dataVariableExpansion
   */
  function testVariableExpansion($options, $key, $expected) {
    $settings = new Settings($options);
    $this->assertEquals($expected, $settings->get($key));
  }
  
  function dataVariableExpansion() {
    return array(
      array(
        array('foo' => 'FFF', 'bar' => '{foo}/BAR'), 'bar', 'FFF/BAR'
      ),
      array(
        array('foo' => 'FOO', 'bar' => 'BAR', 'baz' => '{foo}{bar}'),
        'baz', 'FOOBAR'
      ),
      array(
        array('foo' => '{unknown}/bar'), 'foo', '{unknown}/bar'
      ),
      array(
        array('foo' => '{{strange_text}}'), 'foo', '{{strange_text}}'
      ),
      array(
        array('foo' => '{foo}F'), 'foo', '{foo}F'
      ),
    );
  }

}
