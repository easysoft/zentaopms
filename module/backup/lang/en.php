<?php
$lang->backup->common      = 'Backup';
$lang->backup->index       = 'Backup Home';
$lang->backup->history     = 'History';
$lang->backup->delete      = 'Delete Backup';
$lang->backup->backup      = 'Backup';
$lang->backup->restore     = 'Restore';
$lang->backup->change      = 'Edit Expiration';
$lang->backup->changeAB    = 'Edit';
$lang->backup->rmPHPHeader = 'Remove PHP header';

$lang->backup->time     = 'Date';
$lang->backup->files    = 'Files';
$lang->backup->allCount = 'All Count';
$lang->backup->count    = 'Backup Count';
$lang->backup->size     = 'Size';
$lang->backup->status   = 'Status';

$lang->backup->statusList['success'] = 'Success';
$lang->backup->statusList['fail']    = 'Fail';

$lang->backup->setting    = 'Settings';
$lang->backup->settingDir = 'Backup Directory';
$lang->backup->settingList['nofile'] = 'Do not back up files or codes.';
$lang->backup->settingList['nosafe'] = 'Do not prevent downloading PHP file header.';

$lang->backup->waitting       = '<span id="backupType"></span> is ongoing. Please wait...';
$lang->backup->progressSQL    = '<p>SQL backup: %s is backed up.</p>';
$lang->backup->progressAttach = '<p>SQL backup is completed.</p><p>Attachment backing up.</p>';
$lang->backup->progressCode   = '<p>SQL backup is completed.</p><p>Attachment backup is completed.</p><p>Code backing up.</p>';
$lang->backup->confirmDelete  = 'Do you want to delete the backup?';
$lang->backup->confirmRestore = 'Do you want to restore the backup?';
$lang->backup->holdDays       = 'Hold last %s days of backup';
$lang->backup->copiedFail     = 'Copy failed files: ';
$lang->backup->restoreTip     = 'Only files and databases can be restored by clicking Restore. Code can be restored manually.';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Done!';
$lang->backup->success->restore = 'Restored!';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir = 'Directory does not exist and cannot be created';
$lang->backup->error->noWritable  = "<code>%s</code> is not writable! Please check the privilege, or backup will not be done.";
$lang->backup->error->noDelete    = "%s cannot be deleted. Please modify the privilege or manually delete it.";
$lang->backup->error->restoreSQL  = "Failed to restore the database library. Error %s.";
$lang->backup->error->restoreFile = "Failed to restore the file. Error %s.";
$lang->backup->error->backupFile  = "Failed to back up the file. Error %s.";
$lang->backup->error->backupCode  = "Failed to back up the code. Error %s.";
