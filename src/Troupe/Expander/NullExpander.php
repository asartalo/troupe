<?php

namespace Troupe\Expander;

class NullExpander implements Expander {
  function expand($archive, $to) {
    return array();
  }
}
