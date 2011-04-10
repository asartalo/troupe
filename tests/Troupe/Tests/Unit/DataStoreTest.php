<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\DataStore;

class DataStoreTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->clearTestDataDir();
    $this->data_directory = $this->getTestDataDir();
    $this->data_file = $this->data_directory . '/troupe.dat';
    $this->data_store = new DataStore($this->data_directory);
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  /**
   * @dataProvider dataSetGet
   */
  function testSetGet($collection, $key, $value) {
    $this->data_store->set($collection, $key, $value);
    $this->assertEquals($value, $this->data_store->get($collection, $key));
  }
  
  function dataSetGet() {
    return array(
      array('foo', 'bar', 'baz')
    );
  }
  
  function testSetGet2() {
    $collection = 'foo';
    $key1 = 'bar';
    $value1 = 'baz';
    $key2 = 'A';
    $value2 = 'B';
    $this->data_store->set($collection, $key1, $value1);
    $this->data_store->set($collection, $key2, $value2);
    $this->assertEquals($value1, $this->data_store->get($collection, $key1));
    $this->assertEquals($value2, $this->data_store->get($collection, $key2));
  }
  
  function testSetGetUnknownCollection() {
    $this->assertSame(null, $this->data_store->get('foo', 'bar'));
  }
  
  function testSetGetUnknownKey() {
    $this->data_store->set('foo', 'bar', 'baz');
    $this->assertSame(null, $this->data_store->get('foo', 'boo'));
  }
  
  function testDestructorSerialiazesDataToDataFile() {
    $this->data_store->set('foo', 'bar', 'baz');
    $this->data_store->set('foo', 'A', 'B');
    $this->data_store->set('boo', 'far', 'faz');
    unset($this->data_store);
    $data_file = $this->data_file;
    $this->assertFileExists($this->data_file);
    $data = unserialize(file_get_contents($this->data_file));
    $this->assertEquals('baz', $data['foo']['bar']);
    $this->assertEquals('B', $data['foo']['A']);
    $this->assertEquals('faz', $data['boo']['far']);
  }
  
  function testConstructorImportsDataFromDataFile() {
    $this->data_store->set('foo', 'bar', 'baz');
    $this->data_store->set('foo', 'A', 'B');
    $this->data_store->set('boo', 'far', 'faz');
    unset($this->data_store);
    $newds = new DataStore($this->data_directory);
    $this->assertEquals('baz', $newds->get('foo', 'bar'));
    $this->assertEquals('B', $newds->get('foo', 'A'));
    $this->assertEquals('faz', $newds->get('boo', 'far'));
  }

}
