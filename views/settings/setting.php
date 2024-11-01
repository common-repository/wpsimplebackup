<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Settings of WPSimpleBackup
 */
$settings = [
    'cronschedule' => get_option('wpsimplebackup_cronschedule'),
    'notifyadmin' => get_option('wpsimplebackup_notifyadmin')
];
//
//echo "<pre>";
//print_r($settings);
//echo "</pre>";
//die();
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1>Settings</h1><br>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#check"><span class="dashicons-before dashicons-admin-settings"></span> Check Configuration</a></li>
        <li><a data-toggle="tab" href="#setting"><span class="dashicons-before dashicons-admin-generic"></span> Additional Settings</a></li>
    </ul>
    <div class="tab-content">
        <div id="check" class="margintop20 tab-pane fade in active ">
            <?php
            //Check Required Configurations
            $response = Wp_Simple_Backup\Settings\WpSimpleBackupSettings::checkRequiredConfig();
            foreach ($response as $res) {
                if (!empty($res)) {
                    ?>
                    <div class="alert <?php echo $res->class; ?> fade in alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <span class="dashicons-before <?php echo ($res->error) ? 'dashicons-thumbs-down' : 'dashicons-thumbs-up'; ?>"></span> <?php echo $res->message; ?>
                    </div>
                    <?php
                }
            }
            ?>

            <?php
            //Additional Properties
            $responseArr = \Wp_Simple_Backup\Settings\WpSimpleBackupSettings::getAdditionalSettings();
            if (!empty($responseArr)) {
                ?>
                <table id="configlistings" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($responseArr as $response) {
                            if (!empty($response)) {
                                ?>
                                <tr>
                                    <td><?php echo $response->name; ?></td>
                                    <td><span><?php echo $response->value; ?></span></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
        <div id="setting" class ="margintop20 tab-pane fade">
            <form name="wpsimeplbackupsettings" id="wpsimplebackupsettings" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                <h4>Backup Directories</h4><hr>
                <table id="additionalconfiglistings" class="table table-bordered" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td>Code Backup Directory</td>
                            <td><?php echo Wp_Simple_Backup\Code\WpSimpleBackupCode::$backupDirPath; ?></td>
                        </tr>
                        <tr>
                            <td>Database Backup Directory</td>
                            <td><?php echo Wp_Simple_Backup\Database\WpSimpleBackupDatabase::$backupDirPath; ?></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

