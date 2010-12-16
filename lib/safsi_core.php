<?php
if ( ! defined('__SAFSI_CORE_LOADED') ) {

    define('__SAFSI_CORE_LOADED', true);

    define('SAFSI_LIB', dirname(__FILE__));

    function safsi_core_require_once($filename) {
        require_once(SAFSI_LIB . '/' . $filename);
    }

}
?>
