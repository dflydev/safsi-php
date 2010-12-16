<?php
/**
 * List Item Class.
 * @package safsi
 */

safsi_core_require_once('safsi_core_IListItem.php');

/**
 * List Item Class.
 * @package safsi
 */
class safsi_core_ListItem implements safsi_core_IListItem {

    private $isDirectory;
    private $path;
    private $basename;
    private $dirname;

    public function __construct($isDirectory, $path) {
        $this->isDirectory = $isDirectory;
        $this->path = $path;
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

}

?>
