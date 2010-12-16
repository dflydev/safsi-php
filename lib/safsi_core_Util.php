<?php
/**
 * Utilities.
 * @package safsi
 */

/**
 * Utilities.
 * @package safsi
 */
class safsi_core_Util {

    /**
     * Default content type
     */
    static public $DEFAULT_CONTENT_TYPE = 'application/octet-stream';

    /**
     * Trim leading '/' characters from input
     * @param string $input Path
     * @return string Trimmed path
     */
    static public function TRIM_LEADING($input = null) {
        if ( $input !== null ) {
            $input = preg_replace('|^/+|', '', $input);
        }
        return $input;
    }

    /**
     * Trim trailing '/' characters from input
     * @param string $input Path
     * @return string Trimmed path
     */
    static public function TRIM_TRAILING($input = null) {
        if ( $input !== null ) {
            $input = preg_replace('|/+$|', '', $input);
        }
        return $input;
    }

    /**
     * Trim leading and trailing '/' characters from input
     * @param string $input Path
     * @return string Trimmed path
     */
    static public function TRIM_BOTH($input = null) {
        if ( $input !== null ) {
            $input = self::TRIM_LEADING(self::TRIM_TRAILING($input));
        }
        return $input;
    }

    /**
     * Generate a path from input
     * @param string
     * @return string Path
     */
    static public function GENERATE_PATH_FROM_PARTS() {
        $parts = func_get_args();
        return implode('/', $parts);
    }

    /**
     * Get meta information for specified data
     * @param mixed $data Data
     * @param string $contentType Content type
     * @param string $contentLength Content length
     * @param string $checksum Checksum
     * @return array $contentType, $contentLength and $checksum
     */
    static public function GET_META_FOR_DATA($data = null, $contentType = null, $contentLength = null, $checksum = null) {
        if ( $contentType === null ) $contentType = self::$DEFAULT_CONTENT_TYPE;
        if ( $contentLength === null ) $contentLength = strlen($data);
        if ( $checksum === null ) $checksum = md5($data);
        return array($contentType, $contentLength, $checksum);
    }

    /**
     * Get meta information for specified file
     * @param string $path Path
     * @param string $contentType Content type
     * @param string $contentLength Content length
     * @param string $checksum Checksum
     * @return array $contentType, $contentLength and $checksum
     */
    static public function GET_META_FOR_PATH($path = null, $contentType = null, $contentLength = null, $checksum = null) {
        if ( $contentType === null ) {
            $contentType = self::DETERMINE_MIME_TYPE($path);
        }
        if ( $contentLength === null ) $contentLength = filesize($path);
        if ( $checksum === null ) $checksum = md5_file($path);
        return array($contentType, $contentLength, $checksum);
    }

    /**
     * Determine a sane MIME type for a specified filename.
     *
     * Uses finfo to try and determine a MIME type for a specified
     * filename. If no MIME type is located, it will return a sane
     * default (application/octet-stream).
     *
     * Borrowed heavily from:
     * http://us.php.net/manual/en/function.finfo-file.php#82757
     * @param string $filename Filename
     * @returns string MIME type.
     */
    static public function DETERMINE_MIME_TYPE($filename, $default = null) {
        if ( $default === null ) $default = self::$DEFAULT_CONTENT_TYPE;
        try {
            if ( function_exists('finfo_open') ) {
                // If the finfo functions exist, use those.
                $finfo = finfo_open(FILEINFO_MIME);
                if ($finfo !== FALSE) {
                    $fres = finfo_file($finfo, $filename);
                    if ( ($fres !== FALSE) && is_string($fres) && (strlen($fres)>0) ) {
                        $default = $fres;
                    }
                    finfo_close($finfo);
                }
            } else {
                // Default to standard PHP method.
                $default = mime_content_type($filename);
            }
        } catch (Exception $e) {
            // noop
        }
        return $default;
    }

}
?>
