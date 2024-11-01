<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * Listing of WPSimple Backup
 */

//Backup Code
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'backupcode')) {
    $res = Wp_Simple_Backup\Code\WpSimpleBackupCode::getCodeBackup();
}
//Backup Database
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'backupdatabase')) {
    $res = Wp_Simple_Backup\Database\WpSimpleBackupDatabase::getDatabaseBackup();
}

//Delete code backup
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'deletecodebackup') && (isset($_REQUEST['backupid'])) && (!empty($_REQUEST['backupid']))) {
    Wp_Simple_Backup\Code\WpSimpleBackupCode::deleteCodeBackup($_REQUEST['backupid']);
}

//Delete database backup
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'deletedatabasebackup') && (isset($_REQUEST['backupid'])) && (!empty($_REQUEST['backupid']))) {
    Wp_Simple_Backup\Database\WpSimpleBackupDatabase::deleteDatabaseBackup($_REQUEST['backupid']);
}

//Download Backups
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'downloadbackup') && isset($_REQUEST['downloadfile']) && (!empty($_REQUEST['downloadfile']))) {
    Wp_Simple_Backup\Helper\WpSimpleBackupHelper::downloadBackup();
}
?>
<div id="overlay"><div id="overlaytext"></div></div>
<div class = "wrap">
    <h1>All Backups</h1><br>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#code"><span class="dashicons-before dashicons-media-code"></span> Code</a></li>
        <li data-field="db"><a data-toggle="tab" href="#database"><span class="dashicons-before dashicons-archive"></span> Database</a></li>
    </ul>
    <div class="tab-content">
        <div id="code" class="margintop20 tab-pane fade in active">
            <button type="button" id="codebackupbtn" class="btn btn-success margin10" onclick="window.location.href = document.URL + '&action=backupcode';"><span class="glyphicon glyphicon-console"></span> Backup Code</button>
            <table id="codelisting" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>File Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>

                    <?php
                    $files = Wp_Simple_Backup\Code\WpSimpleBackupCode::getAllCodeBackups();
                    if (!empty($files)):
                        foreach ($files as $fileObject):
                            ?>
                            <tr>
                                <td><?php echo $fileObject->getFilename(); ?></td>
                                <td><?php echo $fileObject->created; ?></td>
                                <td><button class="btn btn-default btn-xs downloadbackup" onclick="window.location.href = document.URL + '&action=downloadbackup&downloadfile=<?php echo $fileObject->getFilename(); ?>'" data-toggle="tooltip" data-placement="top" title="Download"><span class="dashicons-before dashicons-download"></span></button>&nbsp;&nbsp;<button id="deletecodebackupbtn" class="btn btn-default btn-xs" onclick="window.location.href = document.URL + '&action=deletecodebackup&backupid=<?php echo $fileObject->getFilename(); ?>'" data-toggle="tooltip" data-placement="top" title="Delete"><span class="dashicons-before dashicons-no-alt"></span></button>&nbsp;&nbsp;</td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>

        </div>
        <div id="database" class="margintop20 tab-pane fade">
            <button type="button" id="databasebackupbtn" class="btn btn-success margin10" onclick="window.location.href = document.URL + '&action=backupdatabase'"><span class="glyphicon glyphicon-hdd"></span> Backup Database</button>
            <table id="databaselisting" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>File Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $files = Wp_Simple_Backup\Database\WpSimpleBackupDatabase::getAllDatabaseBackups();
                    if (!empty($files)):
                        foreach ($files as $fileObject):
                            ?>
                            <tr>
                                <td><?php echo $fileObject->getFilename(); ?></td>
                                <td><?php echo $fileObject->created; ?></td>
                                <td><button class="btn btn-default btn-xs downloadbackup" onclick="window.location.href = document.URL + '&action=downloadbackup&downloadfile=<?php echo $fileObject->getFilename(); ?>'" data-toggle="tooltip" data-placement="top" title="Download"><span class="dashicons-before dashicons-download"></span></button>&nbsp;&nbsp;<button id="deletedatabasebackupbtn" class="btn btn-default btn-xs" onclick="window.location.href = document.URL + '&action=deletedatabasebackup&backupid=<?php echo $fileObject->getFilename(); ?>'" data-toggle="tooltip" data-placement="top" title="Delete"><span class="dashicons-before dashicons-no-alt"></span></button>&nbsp;&nbsp;</td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
