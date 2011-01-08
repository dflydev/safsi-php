<?php

require_once 'PHPUnit/Framework.php';

set_include_path(get_include_path() . PATH_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib');
set_include_path(get_include_path() . PATH_SEPARATOR . 'lib');

require_once('safsi_localdisk_Util.php');

// Is this safe?
ini_set('error_reporting', E_ALL);

abstract class AbstractSafsiTest extends PHPUnit_Framework_TestCase {

    protected function generatePathFromParts() {
        $parts = func_get_args();
        return call_user_func_array(
            array('safsi_core_Util', 'GENERATE_PATH_FROM_PARTS'),
            $parts
        );
    }

    protected function mkTempDir($prefix = null) {
        if ( $prefix === null ) $prefix = 'abstractSafsiTest';
        $tempDir = $this->generatePathFromParts(
            sys_get_temp_dir(),
            $prefix . '-' . uniqid(rand(),TRUE)
        );
        mkdir($tempDir);
        return $tempDir;
    }

    /**
     * Remove a directory
     *
     * Recursively remove a directory and all of its contents.
     */
    protected function rmDir($directory) {
        return call_user_func(array('safsi_localdisk_Util', 'RM_DIR'), $directory);
    }

    protected function handleLingeringException($e = null) {

        if ( $e instanceof PHPUnit_Framework_AssertionFailedError ) {
            throw $e;
        }

        if ( $e !== null ) {
            $this->fail('Caught an unknown exception: ' . $e);
        }

    }

}

?>
