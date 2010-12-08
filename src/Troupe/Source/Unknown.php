<?php

namespace Troupe\Source;

use \Troupe\Status\Failure;

class Unknown extends AbstractSource {

  function import() {
    return new Failure(
      \Troupe\Source\STATUS_FAIL, "FAIL: Unknown source."
    );
  }

}
  
