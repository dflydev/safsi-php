<?php
/**
 * List Item Interface.
 * @package safsi
 */


/**
 * List Item Interface.
 * @package safsi
 */
interface safsi_core_IListItem {

    /**
     * Is directory?
     * @return bool Is directory?
     */
    public function isDirectory();

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

}

?>
