<?php

class safsi_exception_Exception extends Exception {

    private $cause;

    public function __construct($message = null, $code = 0, $cause = null) {
        parent::__construct($message, $code);
        $this->cause = $cause;
    }

    public function cause() {
        return $this->cause;
    }

}

require_once('safsi_exception_IoException.php');
require_once('safsi_exception_NotImplementedException.php');

?>
