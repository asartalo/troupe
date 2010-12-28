<?php

namespace Troupe;

class FileWriter {
  
  function copyRemoteFile($from, $to) {
    if ($fd = fopen ($from, "rb")) {
      $local_file = fopen($to, 'wb');
      while(!feof($fd)) {
        fwrite($local_file, fread($fd, 2048));
      }
      fclose($local_file);
    }
    fclose ($fd);
  }
  
}
