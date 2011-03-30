<?php

namespace Troupe\Tests;

require_once realpath(__DIR__ . '/ArgvParser.php');

class ArgvParserTest extends \PHPUnit_Framework_TestCase {
  
  
  function setUp() {
    $this->script_name = 'foo.php';
    $this->parser = new ArgvParser($this->script_name);
  }
  
  function testSettingScriptNameInConstructorAddsScriptNameToResult() {
    $parser = new ArgvParser('bar.php');
    $this->assertEquals(
      array('bar.php'), $parser->parse('')
    );
  }
  
  /**
   * @dataProvider dataParseArguments
   */
  function testParseArguments($args, $result) {
    $this->assertEquals(
      $result, $this->parser->parse($args)
    );
  }
  
  function dataParseArguments() {
    return array(
      array(
        'bar',
        array('foo.php', 'bar')
      ),
      array(
        'command another',
        array('foo.php', 'command', 'another')
      ),
      array(
        ' command  another',
        array('foo.php', 'command', 'another')
      ),
      array(
        'com  --flag1 --flag2',
        array('foo.php', 'com', '--flag1', '--flag2')
      ),
      array(
        'command "With Quotes"',
        array('foo.php', 'command', 'With Quotes')
      ),
      array(
        "command 'With Single Quotes'",
        array('foo.php', 'command', 'With Single Quotes')
      ),
    );
  }
  
  
  
}
  