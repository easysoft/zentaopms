<?php
$lang->backup->common      = 'Sicherung';
$lang->backup->index       = 'Backup List';
$lang->backup->history     = 'Verlauf';
$lang->backup->delete      = 'Löschen';
$lang->backup->backup      = 'Sichern';
$lang->backup->restore     = 'Restore';
$lang->backup->change      = 'Ablaufdatum';
$lang->backup->changeAB    = 'Bearbeiten';
$lang->backup->rmPHPHeader = 'Remove PHP header';
$lang->backup->setting     = 'Settings';

$lang->backup->restoreAction = 'Restore Backup';
$lang->backup->settingAction = 'Backup Settings';

$lang->backup->time     = 'Datum';
$lang->backup->files    = 'Dateien';
$lang->backup->allCount = 'All Count';
$lang->backup->count    = 'Backup Count';
$lang->backup->size     = 'Größe';
$lang->backup->status   = 'Status';

$lang->backup->statusList['success'] = 'Success';
$lang->backup->statusList['fail']    = 'Fail';

$lang->backup->settingDir = 'Backup Directory';
$lang->backup->settingList['nofile'] = 'Do not back up files or codes.';
$lang->backup->settingList['nosafe'] = 'Do not prevent downloading PHP file header.';

$lang->backup->waitting         = '<span id="backupType"></span> In Arbeit. Bitte warten...';
$lang->backup->progressSQL      = '<p>SQL backup: %s is backed up.</p>';
$lang->backup->progressAttach   = '<p>SQL backup is completed.</p><p>Attachment backing up.</p>';
$lang->backup->progressCode     = '<p>SQL backup is completed.</p><p>Attachment backup is completed.</p><p>Code backing up.</p>';
$lang->backup->confirmDelete    = 'Möchten Sie das Backup löschen？';
$lang->backup->confirmRestore   = 'Möchten Sie das Backup wiederherstellen?';
$lang->backup->holdDays         = 'Behalten der letzen %s Tage der Backups';
$lang->backup->copiedFail       = 'Copy failed files: ';
$lang->backup->restoreTip       = 'Nur Dateien und Datenbanken können wiederhergestellt werden. Code kann manuell wieder hergestellt werden.';
$lang->backup->insufficientDisk = 'Disk space less thanNEED_SPACEG, it may cause insufficient backup space or affect the use, please process it and try again.';
$lang->backup->ongoBackup       = 'ongoing backup';
$lang->backup->cancelBackup     = 'cancel backup';
$lang->backup->getSpaceLoading  = 'Calculate backup space';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Erledigt!';
$lang->backup->success->restore = 'Wiederhergestellt!';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir     = 'Directory does not exist and cannot be created';
$lang->backup->error->noWritable      = "<code>%s</code> ist nicht beschreibbar! Bitte prüfen Sie die Berechtigungen, ansonsten kann das Backup nicht erstellt werden.";
$lang->backup->error->plainNoWritable = "%s ist nicht beschreibbar! Bitte prüfen Sie die Berechtigungen, ansonsten kann das Backup nicht erstellt werden.";
$lang->backup->error->noDelete        = "%s kann nicht gelöscht werden. Bitte passen Sie die Berechtigungen an oder löschen Sie es manuell.";
$lang->backup->error->restoreSQL      = "Datenbankwiederherstellung fehlgeschlagen. Error: %s.";
$lang->backup->error->restoreFile     = "Dateiwiederherstellung fehlgeschlagen. Error: %s.";
$lang->backup->error->backupFile      = "Dateibackup fehlgeschlagen. Error: %s.";
$lang->backup->error->backupCode      = "Codebackup fehlgeschlagen. Error: %s.";
$lang->backup->error->timeout         = "Backup timeout.";
