<?php

require_once('safsi_exception_Exception.php');

class safsi_exception_IoException extends safsi_exception_Exception {
    public function __construct($message, $cause = null) {
        parent::__construct($message, null, $cause);
    }
}

?>
