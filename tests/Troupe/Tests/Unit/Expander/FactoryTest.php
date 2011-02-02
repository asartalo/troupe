<?php
namespace Troupe\Tests\Unit\Expander;

require_once realpath(__DIR__ . '/../../../../bootstrap.php');

use \Troupe\Expander\Factory;
use \Troupe\Utilities;

class FactoryTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->factory = new Factory(new Utilities);
  }
  
  /**
   * @dataProvider dataGettingExpander
   */
  function testGettingExpander($expander_class, $url) {
    $this->assertInstanceOf($expander_class, $this->factory->getExpander($url));
  }
  
  function dataGettingExpander() {
    return array(
      array('Troupe\Expander\Zip', 'http://example.com/ziparchive.zip'),
      array('Troupe\Expander\Gzip', 'http://example.com/gziparchive.gz'),
      array('Troupe\Expander\Tar', 'http://example.com/tarfile.tar'),
      array('Troupe\Expander\Tgz', 'http://example.com/tgzarchive.tgz'),
      array('Troupe\Expander\Tgz', 'http://example.com/tgzarchive.tar.gz'),
    );
  }

}
