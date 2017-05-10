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
$lang->backup->restoreTip     = 'Only attachments and databases will be restored when you click restore button, if you need to restore the code, you can restore manually.';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Backed up!';
$lang->backup->success->restore = 'Restored!';

$lang->backup->error = new stdclass();
$lang->backup->error->noWritable  = "<code>%s</code> is not writable! Please check the permission, or back up cannot be done.";
$lang->backup->error->noDelete    = "%s cannot be deleted. Please modify the permission or manually delete it.";
$lang->backup->error->restoreSQL  = "Database library restoring failed. Error %s.";
$lang->backup->error->restoreFile = "File restoring failed. Error %s.";
$lang->backup->error->backupFile  = "File backup failed. Error %s.";
$lang->backup->error->backupCode  = "Code backup failed. Error %s.";
