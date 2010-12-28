<?php

namespace Troupe\Expander;

interface Expander {
  function expand($archive, $to);
}