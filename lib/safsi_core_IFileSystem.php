<?php
/**
 * File System Interface.
 * @package safsi
 */


/**
 * File System Interface.
 * @package safsi
 */
interface safsi_core_IFileSystem {

    /**
     * Delete a directory.
     * @param mixed $path Directory path
     * @param bool $recursive Recursively delete directory?
     * @return bool Success
     */
    public function deleteDirectory($path, $recursive = false);

    /*
     * Delete a file.
     * @param mixed $path File path
     * @return bool Success
     */
    public function deleteFile($path);

    /**
     * Check to see if a path exists
     * @return bool Exists
     */
    public function exists($path);

    /**
     * List items at path
     * @param mixed $path Directory
     * @return bool Exists
     */
    public function listItems($path = null);

    /**
     * Read data from path
     * @param mixed $path Path
     * @return safsi_core_IFile File
     */
    public function read($path);

    /**
     * Read data from path to a stream
     * @param mixed $path Path
     * @param mixed $resource Target
     * @return safsi_core_IFile File
     */
    public function readStream($path, $resource);

    /**
     * Write data to path
     * @param mixed $path Path
     * @param mixed $data Data
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    public function write($path,  $data, $contentType = null, $contentLength = null, $checksum = null);

    /**
     * Write stream to path
     * @param mixed $path Path
     * @param mixed $data Data
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    public function writeStream($path, $resource, $contentType = null, $contentLength = null, $checksum = null);

    /**
     * Write contents of source path to path
     * @param mixed $path Path
     * @param mixed $sourcePath Source path
     * @param string $contentType Content Type
     * @param string $contentLength Content Length
     * @param string $checksum Checksum
     * @return bool Success
     */
    public function writeFromPath($path, $sourcePath, $contentType = null, $contentLength = null, $checksum = null);


    /**
     * Construct a safsi_core_IListItem instance.
     * @param bool $isDirectory Is directory?
     * @param string $path Path
     * @return safsi_core_IListItem
     */
    public function constructListItem($isDirectory, $path);

    /**
     * Construct a safsi_core_IFile instance.
     * @param string $path Path
     * @return safsi_core_IFile
     */
    public function constructFile($path, $data = null, $contentType = null, $contentLength = null, $checksum = null);

}

?>
