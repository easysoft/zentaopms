<?php
$lang->backup->common      = 'Backup';
$lang->backup->name        = 'Backup Name';
$lang->backup->index       = 'Backup Home';
$lang->backup->history     = 'Historique';
$lang->backup->delete      = 'Suppression Backup';
$lang->backup->backup      = 'Backup';
$lang->backup->restore     = 'Restore';
$lang->backup->change      = 'Editer Expiration';
$lang->backup->changeAB    = 'Editer';
$lang->backup->rmPHPHeader = 'Enlever PHP header';
$lang->backup->setting     = 'Paramétrage';

$lang->backup->restoreAction = 'Restore Backup';
$lang->backup->settingAction = 'Backup Settings';

$lang->backup->time     = 'Date';
$lang->backup->files    = 'Fichiers';
$lang->backup->allCount = 'Tous les Compteurs';
$lang->backup->count    = 'Compteur Backup';
$lang->backup->size     = 'Taille';
$lang->backup->status   = 'Statut';

$lang->backup->statusList['success'] = 'Succès';
$lang->backup->statusList['fail']    = 'Echec';

$lang->backup->settingDir = 'Répertoire Backup';
$lang->backup->settingList['nofile'] = 'Ne pas archiver fichiers et codes.';
$lang->backup->settingList['nosafe'] = 'Ne pas prévenir du téléchargement par PHP file header.';

global $config;
if($config->inContainer) $lang->backup->settingList['nofile'] = 'Ne pas archiver fichiers';

$lang->backup->waiting          = '<span id="backupType"></span> est en cours. Patientez s´il vous plait...';
$lang->backup->progressSQL      = '<p>SQL backup: %s est sauvegardé.</p>';
$lang->backup->progressAttach   = '<p>SQL backup est terminé.</p><p>Les fichiers sont en cours de sauvegarde.</p>';
$lang->backup->progressCode     = '<p>SQL backup est terminé.</p><p>Sauvegarde des fichiers terminée.</p><p>Sauvegarde du code en cours.</p>';
$lang->backup->confirmDelete    = 'Voulez-vous supprimer la sauvegarde ?';
$lang->backup->confirmRestore   = 'Voulez-vous restaurer la sauvegarde ?';
$lang->backup->holdDays         = 'conserver les derniers %s jours de backup';
$lang->backup->copiedFail       = 'Fichiers en échec de copie : ';
$lang->backup->restoreTip       = 'Seulement les fichiers et les bases peuvent être restaurées en cliquant sur Restaurer. Le code doit être restauré manuellement.';
$lang->backup->insufficientDisk = 'Disk space less thanNEED_SPACEG, it may cause insufficient backup space or affect the use, please process it and try again.';
$lang->backup->ongoBackup       = 'ongoing backup';
$lang->backup->cancelBackup     = 'cancel backup';
$lang->backup->getSpaceLoading  = 'Calculate backup space';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Terminé avec succès !';
$lang->backup->success->restore = 'Restauré avec succès !';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir     = "Le répertoire n'existe pas et ne peut pas être créer. C'est problématique.";
$lang->backup->error->noWritable      = "<code>%s</code> n'est pas autorisé à l'écriture ! Vérifier les privilèges sinon le backup ne pourra pas se faire.";
$lang->backup->error->plainNoWritable = "%s n'est pas autorisé à l'écriture ! Vérifier les privilèges sinon le backup ne pourra pas se faire.";
$lang->backup->error->noDelete        = "%s ne peut pas être supprimé. Modifiez les privilèges ou supprimez-le avec vos petites mains.";
$lang->backup->error->restoreSQL      = "Echec pour restaurer la database library. Error %s.";
$lang->backup->error->restoreFile     = "Echec pour restaurer le fichier. Erreur %s.";
$lang->backup->error->backupFile      = "Echec pour sauvegarder le fichier. Erreur %s.";
$lang->backup->error->backupCode      = "Echec pour sauvegarder le code. Erreur %s.";
$lang->backup->error->timeout         = "Backup timeout.";
$lang->backup->error->int             = '『%s』should be a positive integer.';

$lang->backup->notice = new stdclass();
$lang->backup->notice->higherVersion     = 'The version of the backup is higher than the current version. Please update the Docker image version to %s before restoring.';
$lang->backup->notice->lowerVersion      = 'The version of the backup is lower than the current version, and the upgrade process will be performed after restoration.';
$lang->backup->notice->unknownVersion    = 'The version information not found. Do you want to restore this backup anyway?';
$lang->backup->notice->settingsInQuickon = 'You are currently using ZenTao DevOps Platform Edition, there is no need to set additional options.';
$lang->backup->notice->gotoUpgrade       = 'Restore successful, will jump to the upgrade page, if not jump, please manually refresh the page';
