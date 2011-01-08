<?php
/**
 * Local Disk File System.
 * @package safsi_localdisk
 */

require_once('safsi_core_AbstractFileSystem.php');

/**
 * Local Disk File System.
 * @package safsi_localdisk
 */
class safsi_localdisk_FileSystem extends safsi_core_AbstractFileSystem {

    /**
     * Root path for the file system.
     * @var string
     */
    private $root;

    /**
     * Create a new Local Disk File System.
     * @param string $root Root path for the file system
     */
    public function __construct($root) {
        $this->root = preg_replace('|/+$|', '', $root);
    }

    /**
     * Delete a directory.
     * @param mixed $path Directory path
     * @param bool $recursive Recursively delete directory?
     * @return bool Success
     */
    public function deleteDirectory($path, $recursive = false) {
        $actualPath = $this->normalizeLocaldiskPath($path);
        if ( file_exists($actualPath) ) {
            if ( is_dir($actualPath) ) {
                safsi_localdisk_Util::RM_DIR($actualPath);
            } else {
                // TODO: Throw "not a directory" exception?
                return false;
            }
        }
        return NULL;
    }

    /*
     * Delete a file.
     * @param mixed $path File path
     * @return bool Success
     */
    public function deleteFile($path) {
        $actualPath = $this->normalizeLocaldiskPath($path);
        if ( file_exists($actualPath) ) {
            return unlink($actualPath);
        }
        return NULL;
    }

    /**
     * Check to see if a path exists
     * @return bool Exists
     */
    public function exists($path) {
        return file_exists($this->normalizeLocaldiskPath($path));
    }

    /**
     * List items at path
     * @param mixed $path Directory
     * @return bool Exists
     */
    protected function listItemsInternal($path = null) {
        $path = $this->normalizePath($path);
        $actualPath = $this->normalizeLocaldiskPath($path);
        $listItems = array();
        if ( ! is_dir($actualPath) ) { return $listItems; }
        if ( $dirHandle = opendir($actualPath) ) {
            while (($file = readdir($dirHandle)) !== false) {
                if ( $file == '.' or $file == '..' ) continue;
                $actualFilePath = $path ? $actualPath . '/' . $file : $file;
                $filePath = $path ? $path . '/' . $file : $file;
                $listItems[] = $this->constructListItem(
                    is_dir($actualFilePath),
                    $filePath
                );
            }
            closedir($dirHandle);
        }
        return $listItems;
    }

    /**
     * Read data from path
     * @param mixed $path Path
     * @return safsi_core_IFile File
     */
    public function read($path) {
        $actualPath = $this->normalizeLocaldiskPath($path);
        list($contentType, $contentLength, $checksum) = $this->getMeta($path);
        return $this->constructFile(
            $path,
            file_get_contents($actualPath),
            $contentType,
            $contentLength,
            $checksum
        );
    }

    /**
     * Read data from path
     * @param mixed $path Path
     * @param mixed $resource Target
     * @return safsi_core_IFile File
     */
    public function readStream($path, $resource) {
        $actualPath = $this->normalizeLocaldiskPath($path);
        list($contentType, $contentLength, $checksum) = $this->getMeta($path);
        $source = fopen($actualPath, 'rb');
        while (!feof($source)) {
            fwrite($resource, fread($source, 1024));
        }
        fclose($source);
        return $this->constructFile(
            $path,
            null,
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
    public function writeInternal($path, $data, $contentType = null, $contentLength = null, $checksum = null) {
        $this->mkdirs($path);
        $actualPath = $this->normalizeLocaldiskPath($path);
        return file_put_contents($actualPath, $data);
    }

    /**
     * Write stream to path
     * @param mixed $path Path
     * @param mixed $data Data
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    public function writeStream($path, $resource, $contentType = null, $contentLength = null, $checksum = null) {
        $this->mkdirs($path);
        $actualPath = $this->normalizeLocaldiskPath($path);
        $out = fopen($actualPath, 'wb');
        while (!feof($resource)) {
            fwrite($out, fread($resource, 1024));
        }
        fclose($out);
        return true;
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
    public function writeFromPathInternal($path, $sourcePath, $contentType = null, $contentLength = null, $checksum = null) {
        $this->mkdirs($path);
        $actualPath = $this->normalizeLocaldiskPath($path);
        return copy($sourcePath, $actualPath);
    }

    /**
     * Normalize a localdisk path
     * @param string $path Path
     * @return string Path
     */
    protected function normalizeLocaldiskPath($path) {
        return $this->root . '/' . $this->normalizePath($path);
    }

    /**
     * Get meta data for a path
     * @param string $path Path
     * @return array Array containing content type, content length and checksum
     */
    protected function getMeta($path) {
        return safsi_core_Util::GET_META_FOR_PATH(
            $this->normalizeLocaldiskPath($path)
        );
    }

    /**
     * Make directories up to the path
     * @param string $path Path
     */
    protected function mkdirs($path) {

        $dir = dirname($this->normalizeLocaldiskPath($path));

        $pathSoFar = '';

        foreach ( explode('/', $dir) as $part ) {

            // Skip "empty" directory parts.
            if ( ! strlen($part) ) continue;

            // Include this part on our path.
            $pathSoFar .= '/' . $part;

            // Skip "existing" directory parts.
            if ( file_exists($pathSoFar) ) continue;

            // Try to create the directory.
            if ( ! mkdir($pathSoFar) ) return false;

        }

        // Assume it is all good here.
        return true;

    }

}

?>
