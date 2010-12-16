<?php
/**
 * File Class.
 * @package safsi
 */

safsi_core_require_once('safsi_core_IFile.php');

/**
 * File Class.
 * @package safsi
 */
class safsi_core_File implements safsi_core_IFile {

    private $path;
    private $basename;
    private $dirname;
    private $contentType;
    private $contentLength;
    private $contentChecksum;

    public function __construct($path, $data = null, $contentType = null, $contentLength = null, $checksum = null) {
        $this->path = $path;
        $this->data = $data;
        $this->contentType = $contentType;
        $this->contentLength = $contentLength;
        $this->checksum = $checksum;
        $this->basename = basename($path);
        $this->dirname = dirname($path);
        if ( $this->dirname == '.' ) $this->dirname = '';
    }

    /**
     * Is directory?
     * @return bool Is directory?
     */
    public function isDirectory() {
    }

    /**
     * Base name
     * @return string base name
     */
    public function basename() {
        return $this->basename;
    }

    /**
     * Directory name
     * @return string directory name
     */
    public function dirname() {
        return $this->dirname;
    }

    /**
     * Path
     * @return string path
     */
    public function path() {
        return $this->path;
    }

    /**
     * Data
     * @return mixed data
     */
    public function data() {
        return $this->data;
    }

    /**
     * Content Type
     * @return string Content Type
     */
    public function contentType() {
        return $this->contentType;
    }

    /**
     * Content Length
     * @return string Content Length
     */
    public function contentLength() {
        return $this->contentLength;
    }

    /**
     * Checksum
     * @return string Checksum
     */
    public function checksum() {
        return $this->checksum;
    }

}

?>
