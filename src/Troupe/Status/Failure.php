<?php

namespace Troupe\Status;

class Failure extends Status {
  
  function isSuccessful() {
    return false;
  }
  
}
