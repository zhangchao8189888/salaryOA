<?php
  class EC {
  CONST VALUE_IS_NULL_MSG="this value is null";
  CONST VALUE_IS_NULL_CODE="2001";
  var $error_str = "";
 function setError($method, $msg) {
              $this->error_str  = get_class($this)."::{$method}(): $msg";
          }
 function error() {
        return $this->error_str;
    }


  }
 ?>
