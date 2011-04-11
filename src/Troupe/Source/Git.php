<?php

namespace Troupe\Source;

class Git extends CommandlineImport {
  
  function getCliCommand($url, $troupe_lib_path) {
    return sprintf(
      'git clone --recursive %s %s',
      escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function getCheckIfSuccess($last_line) {
    return strpos($last_line, 'Initialized empty Git repository in') === 0 ||
      (
        strpos($last_line, 'Submodule path ') === 0 &&
        strpos($last_line, 'checked out ') > 16
      );
  }
  
}
