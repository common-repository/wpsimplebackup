<?php

/**
 * WpSimpleBackupHelper Class Helper utilities.
 *
 * @author kapil
 */

namespace Wp_Simple_Backup\Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Wp_Simple_Backup\Database\WpSimpleBackupDatabase as Databse;
use Wp_Simple_Backup\Code\WpSimpleBackupCode as Code;
use Wp_Simple_Backup\Download\WpSimpleBackupDownload as Download;

abstract class WpSimpleBackupHelper {

    /**
     * Check File Numeric Permission
     *
     * @since   1.0
     */
    public static function getFilePermissions($filePath = null) {
        if (empty($filePath)) {
            return false;
        }
        return exec('stat -c "%a" ' . $filePath . '');
    }

    /**
     * Return Directory File Permission String
     *
     * @since   1.0
     */
    public static function isWritable($numericPermission) {
        if ($numericPermission == 755 || $numericPermission == 777) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sort Array of objects by date
     *
     * @since   1.0
     */
    public static function dateSort($a, $b) {
        return strtotime($a->created) - strtotime($b->created);
    }

    /**
     * Download Code/Database Backup
     * 
     * @since   1.0
     */
    public static function downloadBackup() {

        $user = get_user_by('ID', get_current_user_id());
        if (in_array('administrator', $user->roles)) {
            $fileObject = new \SplFileInfo($_REQUEST['downloadfile']);
            $fileExtension = $fileObject->getExtension();
            $backupDir = ($fileExtension == 'sql') ? Databse::$backupDirPath : Code::$backupDirPath;
            $file = $backupDir . $_REQUEST['downloadfile'];
            if (file_exists($file)) {
                $urlPath = ($fileExtension == '.sql') ? 'database' : 'code';
                $fileObj = new \stdClass();
                $fileObj->name = $_REQUEST['downloadfile'];
                $fileObj->path = $file;
                Download::forceDownloadFile($fileObj);
            } else {
                wp_die('File doesn"t exists!');
            }
        } else {
            wp_die('Unauthorized Request');
        }
    }

}
