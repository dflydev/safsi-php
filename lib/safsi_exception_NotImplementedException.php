<?php

require_once('safsi_exception_Exception.php');

class safsi_exception_NotImplementedException extends safsi_exception_Exception {
    public function __construct($operation) {
        parent::__construct('Operation "' . $operation . '" is not yet implemented');
    }
}

?>
