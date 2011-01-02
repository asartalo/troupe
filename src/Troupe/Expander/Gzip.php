<?php

namespace Troupe\Expander;

class Gzip implements Expander {
  
  function expand($archive, $to) {
    $fp = fopen('compress.zlib://' . $archive, 'rb');
    $new_file = $to . '/' . pathinfo($archive, PATHINFO_FILENAME);
    $extracted_file = fopen($new_file, 'wb');
    fwrite($extracted_file, stream_get_contents($fp));
    fclose($fp);
    fclose($extracted_file);
    return array($new_file);
  }
  
}
