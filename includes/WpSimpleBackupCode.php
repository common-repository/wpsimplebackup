<?php

/**
 * WpSimpleBackupCode handles code backup related operations in WPSimpleBackup.
 *
 * @author kapil
 */

namespace Wp_Simple_Backup\Code;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSimpleBackupCode {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance;
    public static $backupDirPath;
    public static $exportFormat;

    /**
     * Class Constructor
     * 
     * @since   1.0
     */
    private function __construct() {
        static::$backupDirPath = WP_CONTENT_DIR . '/wpsimplebackup/backup/code/';
        static::$exportFormat = '.gz';
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSimpleBackupCode();
        }

        return self::$instance;
    }

    /**
     * Create Code backup
     *
     * @since   1.0
     */
    public static function getCodeBackup() {
        $folder_to_backup = get_home_path();
        $backup_folder = self::$backupDirPath;
        $fileName = 'BACKUP_CODE_' . time();
        exec("tar -cvf $backup_folder/$fileName.tar.gz $folder_to_backup/* --exclude='$folder_to_backup/*tar.gz'", $results, $result_value);
        if ($result_value == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete Code Backup
     *
     * @since   1.0
     */
    public static function deleteCodeBackup($filePath) {
        $filePath = static::$backupDirPath . '/' . $filePath;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Get All Code Backup Files
     * 
     * @since   1.0
     */
    public static function getAllCodeBackups() {
        $response = [];
        $files = glob(self::$backupDirPath . '*' . self::$exportFormat);
        if (!empty($files)) {
            array_multisort(
                    array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files
            );
            foreach ($files as $filePath) {
                $fileObject = new \SplFileObject($filePath);
                $fileObject->created = date("F d, Y H:i:s:A", filemtime($filePath));
                $response[] = $fileObject;
            }
        }

        return $response;
    }

}
