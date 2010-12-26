<?php

namespace Troupe\Source;

class Svn extends CommandlineImport {
  
  function getCliCommand($url, $troupe_lib_path) {
    return sprintf(
      'svn co %s %s', escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function getCheckIfSuccess($last_line) {
    return strpos($last_line, 'Checked out revision ') === 0;
  }
  
}
