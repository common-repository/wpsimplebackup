/* 
 * WPSimpleBackup Javascript Document
 */


(function ($) {

    //Overlay on
    function on(content) {
        var content = (!content) ? '<span class="glyphicon glyphicon-ok"></span> Loading..' : content;
        jQuery("#overlaytext").html(content);
        jQuery("#overlay").show();
    }

    //Overlay Off
    function off() {
        jQuery("#overlaytext").html('');
        jQuery("#overlay").hide();
    }

    jQuery(document).ready(function () {

        jQuery('#codebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Saving Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('show');
        });

        jQuery('#databasebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Saving Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('hide');
            jQuery('.nav-tabs a[href="#database"]').tab('show');
        });

        jQuery('#deletecodebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Deleting Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('show');
        });
        jQuery('#deletedatabasebackupbtn').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Deleting Backup');
            jQuery('.nav-tabs a[href="#code"]').tab('hide');
            jQuery('.nav-tabs a[href="#database"]').tab('show');
        });

        jQuery('.downloadbackup').click(function () {
            on('<br> <span class="glyphicon glyphicon-ok"></span> Downloading Backup');
            setTimeout(function () {
                off();
            }, 3000);
        });

        jQuery('.codetab, .dbtab').click(function () {
            if (jQuery(this).parent().hasClass('active')) {
                window.location.href = window.location.href + '&tab=' + jQuery(this).attr('data-field');
            }
        });

        jQuery('#codelisting').DataTable({
            'processing': true,
            'bProcessing': true,
            'aaSorting': [[1, 'desc']]
        });
        jQuery('#databaselisting').DataTable({
            'processing': true,
            'bProcessing': true,
            'aaSorting': [[1, 'desc']]
        });
    });
}(jQuery));