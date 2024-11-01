<?php

/**
 * WpSimpleBackupSettings handles settings of WPSimpleBackup Plugin.
 *
 * @author kapil
 */

namespace Wp_Simple_Backup\Settings;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WpSimpleBackupSettings {

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

        add_action('admin_menu', array($this, 'addSettingPage'));
    }

    /**
     * Get Singleton Instance
     * 
     * @since   1.0
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new WpSimpleBackupSettings();
        }

        return self::$instance;
    }

    /**
     * Register Main Plugin Page
     */
    public function addSettingPage() {
        add_menu_page(
                __('WP Simple Backup', 'textdomain'), 'WP Simple Backup', 'manage_options', 'wpsimplebackup/views/settings/listing.php', '', 'dashicons-backup', 201
        );

        add_submenu_page(
                'wpsimplebackup/views/settings/listing.php', __('Settings', 'textdomain'), 'Settings', 'manage_options', 'wpsimplebackup/views/settings/setting.php'
        );
    }

    /**
     * Check Required configurations
     *
     * @since   1.0
     */
    public static function checkRequiredConfig() {

        $responseArr = [];

        $response = new \stdClass();
        $response->error = false;
        $response->message = 'Success! All Configuration check pass.';
        $response->class = 'alert-success';


        if (!function_exists('exec')) {
            $response = new \stdClass();
            $response->error = true;
            $response->message = 'Error in "exec" command';
            $response->class = 'alert-danger';

            $responseArr[] = $response;
        }

        if (empty($responseArr)) {
            $responseArr[] = $response;
        }

        return $responseArr;
    }

    /**
     * Additional Configurations
     *
     * @since   1.0
     */
    public static function getAdditionalSettings() {

        $responseArr = [];

        //Project Directory
        $response = new \stdClass();
        $response->name = 'Project Directory';
        $response->value = exec('stat -c "%n  | Permission:(%a)" ' . get_home_path() . '');
        $responseArr[] = $response;

        //Database Variables
        if (defined('DB_NAME')) {
            $response = new \stdClass();
            $response->name = 'Database Name';
            $response->value = DB_NAME;
            $responseArr[] = $response;
        }
        if (defined('DB_USER')) {
            $response = new \stdClass();
            $response->name = 'Database User';
            $response->value = DB_USER;
            $responseArr[] = $response;
        }
        if (defined('DB_PASSWORD')) {
            $response = new \stdClass();
            $response->name = 'Database Password';
            $response->value = DB_PASSWORD;
            $responseArr[] = $response;
        }
        if (defined('DB_HOST')) {
            $response = new \stdClass();
            $response->name = 'Database Host';
            $response->value = DB_HOST;
            $responseArr[] = $response;
        }

        return $responseArr;
    }

}
