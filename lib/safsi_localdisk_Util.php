<?php
/**
 * Local Disk Utilities.
 * @package safsi_localdisk
 */

// We need the core Safsi utilities.
require_once('safsi_core_Util.php');

/**
 * Local Disk Utilities.
 * @package safsi_localdisk
 */
class safsi_localdisk_Util {

    /**
     * Remove a directory
     *
     * Recursively remove a directory and all of its contents.
     */
    public function RM_DIR($directory) {

        if ( ! file_exists($directory) ) {
            return false;
        }

        if ( is_link($directory) ) {
            return unlink($directory);
        }
        if ( is_dir($directory) ) {
            $dh = dir($directory);
            while (false !== $entry = $dh->read()) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                self::RM_DIR(safsi_core_Util::GENERATE_PATH_FROM_PARTS($directory, $entry));
            }
            $dh->close();
            return rmdir($directory);
        } else {
            return unlink($directory);
        }
    }

}
?>
