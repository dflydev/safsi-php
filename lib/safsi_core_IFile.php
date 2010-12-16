<?php
/**
 * File Interface.
 * @package safsi
 */


/**
 * File Interface.
 * @package safsi
 */
interface safsi_core_IFile {

    /**
     * Base name
     * @return string base name
     */
    public function basename();

    /**
     * Directory name
     * @return string directory name
     */
    public function dirname();

    /**
     * Path
     * @return string path
     */
    public function path();

    /**
     * Data
     * @return mixed data
     */
    public function data();

    /**
     * Content Type
     * @return string Content Type
     */
    public function contentType();

    /**
     * Content Length
     * @return string Content Length
     */
    public function contentLength();

    /**
     * Checksum
     * @return string Checksum
     */
    public function checksum();

}

?>
