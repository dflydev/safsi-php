<?php
/**
 * Abstract File System Class.
 * @package safsi
 */

require_once('safsi_core_IFileSystem.php');
require_once('safsi_core_ListItem.php');
require_once('safsi_core_File.php');
require_once('safsi_core_Util.php');

/**
 * Abstract File System Class.
 * @package safsi
 */
abstract class safsi_core_AbstractFileSystem implements safsi_core_IFileSystem {

    private $filterRegex = null;

    /**
     * Given a set of list items, filter any that should be excluded
     * @param array $listItems List items
     * @return array Filtered list items
     */
    protected function filterExcludedListItems($listItems) {
        $listItemsOut = array();
        foreach ( $listItems as $listItemsIdx => $listItem ) {
            if ( ! $this->isIgnored($listItem->basename()) ) {
                $listItemsOut[] = $listItem;
            }
            // Try not to use too much memory.
            unset($listItems[$listItemsIdx]);
        }
        return $listItemsOut;
    }

    /**
     * Is a file ignored?
     * @param string $name File name
     * @return bool Is ignored?
     */
    protected function isIgnored($name) {
        if ( $this->filterRegex === null ) {
            // TODO This method should be configured somehow.
            $globalIgnores = '*.o *.lo *.la *.al .libs *.so *.so.[0-9]* *.a *.pyc *.pyo *.rej *~ #*# .#* .*.swp .DS_Store .svn';
            $this->filterRegex = '%^(' . implode('|', explode(' ', preg_replace('|\*|s', '.*', preg_replace('|\.|s', '\.', $globalIgnores)))) . ')$%';
        }
        return preg_match($this->filterRegex, $name);
    }

    /**
     * Normalize a path
     *
     * Useful for taking input from either a string path or an
     * object that has a path method.
     * @param mixed $path Path
     * @return string $path
     */
    protected function normalizePath($path = null) {
        if ( $path === null ) return '';
        return preg_replace('|^/+|', '', method_exists($path, 'path') ? $path->path() : $path);
    }

    /**
     * List items at path
     * @param mixed $path Directory
     * @return bool Exists
     */
    final public function listItems($path = null) {
        return $this->filterExcludedListItems(
            $this->listItemsInternal($path, $this)
        );
    }

    /**
     * List items at path
     * @param mixed $path Directory
     * @return bool Exists
     */
    abstract protected function listItemsInternal($path = null);

    /**
     * Write data to path
     * @param mixed $path Path
     * @param mixed $data Data
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    final public function write($path, $data, $contentType = null, $contentLength = null, $checksum = null) {
        list($contentType, $contentLength, $checksum) = safsi_core_Util::GET_META_FOR_DATA(
            $data,
            $contentType,
            $contentLength,
            $checksum
        );
        return $this->writeInternal(
            $path,
            $data,
            $contentType,
            $contentLength,
            $checksum
        );
    }

    /**
     * Write data to path
     * @param mixed $path Path
     * @param mixed $data Data
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    abstract public function writeInternal($path, $data, $contentType = null, $contentLength = null, $checksum = null);

    /**
     * Write contents of source path to path
     * @param mixed $path Path
     * @param mixed $sourcePath Source path
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    final public function writeFromPath($path, $sourcePath, $contentType = null, $contentLength = null, $checksum = null) {
        list($contentType, $contentLength, $checksum) = safsi_core_Util::GET_META_FOR_PATH(
            $sourcePath,
            $contentType,
            $contentLength,
            $checksum
        );
        return $this->writeFromPathInternal(
            $path,
            $sourcePath,
            $contentType,
            $contentLength,
            $checksum
        );
    }

    /**
     * Write contents of source path to path
     * @param mixed $path Path
     * @param mixed $sourcePath Source path
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    abstract public function writeFromPathInternal($path, $sourcePath, $contentType = null, $contentLength = null, $checksum = null);

    /**
     * Construct a safsi_core_IListItem instance.
     * @param bool $isDirectory Is directory?
     * @param string $path Path
     * @return safsi_core_IListItem
     */
    public function constructListItem($isDirectory, $path) {
        return new safsi_core_ListItem($isDirectory, $path);
    }

    /**
     * Construct a safsi_core_IFile instance.
     * @param string $path Path
     * @return safsi_core_IFile
     */
    public function constructFile($path, $data = null, $contentType = null, $contentLength = null, $checksum = null) {
        return new safsi_core_File(
            $path,
            $data,
            $contentType,
            $contentLength,
            $checksum
        );
    }

}

?>
