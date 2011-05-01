<?php

namespace Troupe\Source;

class Svn extends CommandlineImport {
  
  function getCliCheckOutCommand($url, $troupe_lib_path) {
    return sprintf(
      'svn co %s %s', escapeshellarg($url), escapeshellarg($troupe_lib_path)
    );
  }
  
  function getCliUpdateCommand($url, $troupe_lib_path) {
    return sprintf(
      "svn update %s", escapeshellarg($troupe_lib_path)
    );
  }
  
}
