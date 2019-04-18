<?php
$lang->backup->common      = 'Backup';
$lang->backup->index       = 'Home';
$lang->backup->history     = 'History';
$lang->backup->delete      = 'Delete';
$lang->backup->backup      = 'Back Up';
$lang->backup->restore     = 'Restore';
$lang->backup->change      = 'Modify Expiration';
$lang->backup->changeAB    = 'Modify';
$lang->backup->rmPHPHeader = 'Remove PHP header';

$lang->backup->time  = 'Date';
$lang->backup->files = 'Files';
$lang->backup->size  = 'Size';

$lang->backup->setting    = 'Settings';
$lang->backup->settingDir = 'Backup Directory';
$lang->backup->settingList['nofile'] = 'No backup files and codes.';
$lang->backup->settingList['nozip']  = 'Only copy files and no zip.';
$lang->backup->settingList['nosafe'] = 'No prevent downloading PHP file header.';

$lang->backup->waitting       = '<span id="backupType"></span> In Progress. Please wait...';
$lang->backup->progressSQL    = '<p>SQL backup, %s is backed up.</p>';
$lang->backup->progressAttach = '<p>SQL backup completed.</p><p>Attachment backup, %s is backed up.</p>';
$lang->backup->progressCode   = '<p>SQL backup completed.</p><p>Attachment backup completed.</p><p>Code backup, %s isbacked up.</p>';
$lang->backup->confirmDelete  = 'Do you want to delete the backup?';
$lang->backup->confirmRestore = 'Do you want to restore the backup?';
$lang->backup->holdDays       = 'Reserve last %s days of backup';
$lang->backup->restoreTip     = 'Only files and databases can be restored by clicking Restore. Code can be restored manually.';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Done!';
$lang->backup->success->restore = 'Restored!';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir = 'Directory does not exist, and cannot be created';
$lang->backup->error->noWritable  = "<code>%s</code> is not writable! Please check the privilege, or backup will not be done.";
$lang->backup->error->noDelete    = "%s cannot be deleted. Please modify the privilege or manually delete it.";
$lang->backup->error->restoreSQL  = "Restoring database library failed. Error %s.";
$lang->backup->error->restoreFile = "File restoration failed. Error %s.";
$lang->backup->error->backupFile  = "File backup failed. Error %s.";
$lang->backup->error->backupCode  = "Code backup failed. Error %s.";
