<?php

/**
 * WpSimpleBackupDatabase Handles Database backup related operations in WPSimpleBackup.
 *
 * @author kapil
 */

namespace Wp_Simple_Backup\Database;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSimpleBackupDatabase {

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
        static::$backupDirPath = WP_CONTENT_DIR . '/wpsimplebackup/backup/database/';
        static::$exportFormat = '.sql';
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSimpleBackupDatabase();
        }

        return self::$instance;
    }

    /**
     * Get All Database Backup Files
     * 
     * @since   1.0
     */
    public static function getAllDatabaseBackups() {
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

    /**
     * Delete Database Backup
     *
     * @since   1.0
     */
    public static function deleteDatabaseBackup($filePath) {
        $filePath = static::$backupDirPath . '/' . $filePath;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Create Database backup
     *
     * @since   1.0
     */
    public static function getDatabaseBackup() {
        $mysql_host = DB_HOST;
        $mysql_user = DB_USER;
        $mysql_pass = DB_PASSWORD;
        $mysql_database = DB_NAME;
        $backup_folder = static::$backupDirPath;
        $fileName = 'BACKUP_DATABSE_' . DB_NAME . "_" . time();
        exec("mysqldump -h $mysql_host -u $mysql_user -p$mysql_pass $mysql_database  > $backup_folder/$fileName.sql", $results, $result_value);
        if ($result_value == 0) {
            return true;
        } else {
            return false;
        }
    }

}
