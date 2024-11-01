<?php

/**
 * Download Helper Class
 * 
 * @author kapil
 */

namespace Wp_Simple_Backup\Download;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSimpleBackupDownload {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance;

    /**
     * Class Constructor
     * 
     * @since   1.0
     */
    private function __construct() {
        
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSimpleBackupDownload();
        }

        return self::$instance;
    }

    /**
     * Download File Forcefully
     * 
     * @since   1.0
     */
    public static function forceDownloadFile($dl_file) {
        if (is_file($dl_file->path)) {
            header("Cache-Control: no-store");
            header("Expires: 0");
            header("Content-Type: 'application/octet-stream");
            header("Content-disposition: attachment; filename=\"" . $dl_file->name . "\"");
            header("Content-Transfer-Encoding: binary");
            header('Content-Length: ' . filesize($dl_file->path));
            readfile($dl_file->path);
        }
    }

}
