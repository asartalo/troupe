<?php

namespace Troupe\Expander;

class Factory {

  private $known_types = array(
    'zip' => 'Zip',
    'gz'  => 'Gzip',
    'tar' => 'Tar',
    'tgz' => 'Tgz'
  );
  
  function getExpander($url) {
    $path_info = pathinfo($url);
    $ext = $path_info['extension'];
    if (isset($this->known_types[$ext])) {
      if ($ext == 'gz' && pathinfo($path_info['filename'], PATHINFO_EXTENSION) == 'tar') {
        $ext = 'tgz';
      }
      $class = "Troupe\Expander\\" . $this->known_types[$ext];
      return new $class;
    }
  }
  
}
