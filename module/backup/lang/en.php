<?php
$lang->backup->common      = 'Backup';
$lang->backup->name        = 'Backup Name';
$lang->backup->index       = 'Backup List';
$lang->backup->history     = 'History';
$lang->backup->delete      = 'Delete Backup';
$lang->backup->backup      = 'Start Backup';
$lang->backup->restore     = 'Restore';
$lang->backup->change      = 'Retention days';
$lang->backup->changeAB    = 'Edit';
$lang->backup->rmPHPHeader = 'Remove Security Settings';
$lang->backup->setting     = 'Settings';

$lang->backup->restoreAction = 'Restore Backup';
$lang->backup->settingAction = 'Backup Settings';

$lang->backup->time     = 'Backup Time';
$lang->backup->files    = 'Backup Files';
$lang->backup->allCount = 'Total Files';
$lang->backup->count    = 'Backup Files';
$lang->backup->size     = 'Size';
$lang->backup->status   = 'Status';

$lang->backup->statusList['success'] = 'Success';
$lang->backup->statusList['fail']    = 'Failed';

$lang->backup->settingDir = 'Backup Directory';
$lang->backup->settingList['nofile'] = 'Do not back up files or codes.';
$lang->backup->settingList['nosafe'] = 'Do not prevent downloading PHP file header.';

global $config;
if($config->inContainer) $lang->backup->settingList['nofile'] = 'Do not back up files.';

$lang->backup->waiting          = '<span id="backupType"></span>in progress. Please wait...';
$lang->backup->progressSQL      = '<p>SQL backup in progress — %s completed.</p>';
$lang->backup->progressAttach   = '<p>SQL backup completed.</p><p>Backing up attachments — total: %s files, backed up: %s files.</p>';
$lang->backup->progressCode     = '<p>SQL backup completed.</p><p>Attachment backup completed.</p><p>Backing up source code — total: %s files, backed up: %s files.';
$lang->backup->confirmDelete    = 'Are you sure you want to delete the backup?';
$lang->backup->confirmRestore   = 'Are you sure you want to restore the backup?';
$lang->backup->holdDays         = 'Keep backups for the last %s days.';
$lang->backup->copiedFail       = 'Files failed to copy:';
$lang->backup->restoreTip       = 'The restore process only restores attachments and the database. To restore source code, please do so manually.';
$lang->backup->insufficientDisk = 'Available disk space is less than NEED_SPACEG. This may cause insufficient space for the backup or affect system performance. Please resolve the issue and try again.';
$lang->backup->ongoBackup       = 'Continue Backup';
$lang->backup->cancelBackup     = 'cancel backup';
$lang->backup->getSpaceLoading  = 'Calculating required backup space...';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Backup completed successfully!';
$lang->backup->success->restore = 'Restore completed successfully!';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir     = 'The backup directory does not exist and could not be created.';
$lang->backup->error->noWritable      = "<code>%s</code> is not writable! Please check directory permissions, or the backup cannot proceed.";
$lang->backup->error->plainNoWritable = "%s is not writable! Please check directory permissions, or the backup cannot proceed.";
$lang->backup->error->noDelete        = "File %s cannot be deleted. Please adjust file permissions or delete it manually.";
$lang->backup->error->restoreSQL      = "Database restore failed — error: %s";
$lang->backup->error->restoreFile     = "Attachment restore failed — error: %s";
$lang->backup->error->backupFile      = "Attachment backup failed — error: %s";
$lang->backup->error->backupCode      = "Source code backup failed — error: %s";
$lang->backup->error->timeout         = "Backup timed out.";
$lang->backup->error->int             = '『%s』should be a positive integer.';

$lang->backup->notice = new stdclass();
$lang->backup->notice->higherVersion     = 'The backup version is higher than the current running version. Please update your system image to version %s before restoring.';
$lang->backup->notice->lowerVersion      = 'The backup version is lower than the current running version. The system will perform an upgrade after restoration.';
$lang->backup->notice->unknownVersion    = 'Version information could not be detected from the selected backup. Do you still want to proceed with restoration?';
$lang->backup->notice->settingsInQuickon = 'You are currently using the ZenTao DevOps Platform Edition — no additional configuration is required.';
$lang->backup->notice->gotoUpgrade       = 'Restore completed successfully. Redirecting to the upgrade page — if the page does not refresh automatically, please reload manually.';
