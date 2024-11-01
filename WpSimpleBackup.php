<?php

/*
 * Plugin Name: WPSimpleBackup
 * Plugin URI: http://infobeans.com
 * Description: WPSimpleBackup is a plugin for taking backup of website <strong>Code</strong> and <strong>Database</strong> and store in predefined location.
 * Version: 1.0
 * Author: <a href="https://profiles.wordpress.org/lpkapil008">Kapil Yadav</a>
 * Author URI: http://infobeans.com/
 */

/**
 * WPSimpleBackup Main Plugin class continas plugin Initialization methods.
 * 
 * @author kapil
 */

namespace Wp_Simple_Backup;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSimpleBackup {

    /**
     * Class Member Variables
     *
     * @since   1.0
     */
    private static $instance;
    private $version;
    public static $backupDir;

    /**
     * Class Constructor
     *
     * @since   1.0
     */
    private function __construct() {
        ob_start();
        $this->version = '1.0';
        static::$backupDir = [
            'backuproot' => WP_CONTENT_DIR . '/wpsimplebackup',
            'backupmain' => WP_CONTENT_DIR . '/wpsimplebackup/backup',
            'backupcode' => WP_CONTENT_DIR . '/wpsimplebackup/backup/code',
            'backupdb' => WP_CONTENT_DIR . '/wpsimplebackup/backup/database'
        ];
        $this->createBackupDir();
        $this->protectBackupDir([
            'code' => [
                'path' => static::$backupDir['backupcode'] . '/.htaccess',
                'format' => 'gz'
            ],
            'db' => [
                'path' => static::$backupDir['backupdb'] . '/.htaccess',
                'format' => 'sql'
            ]
        ]);
        $this->getLoader();
    }

    /**
     * Get AutoLoader
     *
     * @since   1.0
     */
    private function getLoader() {
        require_once __DIR__ . '/vendor/autoload.php';
        $this->loadInstances();
        $this->loadActions();
    }

    /**
     * load Instances
     *
     * @since   1.0
     */
    private function loadInstances() {
        Settings\WpSimpleBackupSettings::getInstance();
        Code\WpSimpleBackupCode::getInstance();
        Database\WpSimpleBackupDatabase::getInstance();
    }

    /**
     * load Actions
     *
     * @since   1.0
     */
    private function loadActions() {
        add_action('admin_enqueue_scripts', array($this, 'loadAssets'));
    }

    /**
     * Craete Backup Directory
     * 
     * @since   1.0
     */
    private function createBackupDir() {
        foreach (static::$backupDir as $dir) {
            if (!file_exists($dir)) {
                if (!mkdir($dir)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Protect Backup directories
     * 
     * @since   1.0
     */
    private function protectBackupDir($files) {
        foreach ($files as $file) {
            if (!file_exists($file['path'])) {
                $fileHandle = fopen($file['path'], 'w');
                $fileContent = '<FilesMatch "\.(?:' . $file['format'] . ')$">' . PHP_EOL;
                $fileContent .= 'Order allow,deny' . PHP_EOL;
                $fileContent .= 'Deny from all' . PHP_EOL;
                $fileContent .= '</FilesMatch>' . PHP_EOL;
                fwrite($fileHandle, $fileContent);
                fclose($fileHandle);
            }
        }
    }

    /**
     * load Assets
     *
     * @since   1.0
     */
    public function loadAssets() {
        $pluginInfo = get_plugin_data(__FILE__);
        //Load plugin admin assets on specific pages only
        if ((isset($_REQUEST['page'])) && ( ($_REQUEST['page'] == $pluginInfo['TextDomain'] . '/views/settings/listing.php') || ($_REQUEST['page'] == $pluginInfo['TextDomain'] . '/views/settings/setting.php'))) {
            wp_enqueue_style('wpsimplebackupstyle', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            wp_enqueue_style('wpsimplebackupbootstrapcss', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');
            wp_enqueue_style('wpsimplebackupdatatablecss', plugin_dir_url(__FILE__) . 'assets/css/jquery.dataTables.min.css');
            wp_enqueue_style('wpsimplebackupdatatablebootstrapcss', plugin_dir_url(__FILE__) . 'assets/css/dataTables.bootstrap.min.css');
            wp_enqueue_script('wpsimplebackupbootstrapjs', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'));
            wp_enqueue_script('wpsimplebackupdatatablejs', plugin_dir_url(__FILE__) . 'assets/js/jquery.dataTables.min.js', array('jquery'));
            wp_enqueue_script('wpsimplebackupscriptjs', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'));
        }
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSimpleBackup();
        }

        return self::$instance;
    }

}

WpSimpleBackup::getInstance();
