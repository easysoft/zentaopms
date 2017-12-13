<?php
$lang->backup->common   = 'Backup';
$lang->backup->index    = 'Home';
$lang->backup->history  = 'History';
$lang->backup->delete   = 'Delete';
$lang->backup->backup   = 'Backup';
$lang->backup->restore  = 'Restore';
$lang->backup->change   = 'Save Interval';
$lang->backup->changeAB = 'Modify';

$lang->backup->time  = 'Time';
$lang->backup->files = 'Files';
$lang->backup->size  = 'Size';

$lang->backup->waitting       = '<span id="backupType"></span>In Progree. Please wait...';
$lang->backup->confirmDelete  = 'Do you want to delete the backup？';
$lang->backup->confirmRestore = 'Do you want to restore the backup？';
$lang->backup->holdDays       = 'Backup the latest %s days.';
$lang->backup->restoreTip     = 'Only files and databases will be restored when you click Restore. If you want to restore the code, restore it manually.';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Done!';
$lang->backup->success->restore = 'Restored!';

$lang->backup->error = new stdclass();
$lang->backup->error->noWritable  = "<code>%s</code> is not writable! Please check the privilege, or backup cannot be done.";
$lang->backup->error->noDelete    = "%s cannot be deleted. Please modify the privilege or manually delete it.";
$lang->backup->error->restoreSQL  = "Database library restoration failed. Error %s.";
$lang->backup->error->restoreFile = "File restoration failed. Error %s.";
$lang->backup->error->backupFile  = "File backup failed. Error %s.";
$lang->backup->error->backupCode  = "Code backup failed. Error %s.";
