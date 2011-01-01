<?php
namespace Troupe\Tests\Unit;

require_once realpath(__DIR__ . '/../../../bootstrap.php');

use \Troupe\FileWriter;

class FileWriterTest extends \Troupe\Tests\TestCase {

  function setUp() {
    $this->fwriter = new FileWriter;
    $this->test_copy_file = __DIR__ . '/../../../bootstrap.php';
    $this->the_test_file = $this->getTestDataDir() . '/test';
  }
  
  // TODO: Please improve this fragile test
  function testCopyingFile() {
    $this->fwriter->copyRemoteFile(
      $this->test_copy_file, $this->the_test_file
    );
    $this->assertFileExists($this->the_test_file);
    $this->assertEquals(
      file_get_contents($this->test_copy_file),
      file_get_contents($this->the_test_file)
    );
  }

}
