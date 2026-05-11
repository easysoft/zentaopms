<?php
$lang->zai->setting    = 'Configuration ZAI';
$lang->zai->appID      = 'ID Application';
$lang->zai->host       = 'Hôte';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'Clé Application';
$lang->zai->adminToken = 'Clé Admin';
$lang->zai->addSetting = 'Ajouter Configuration ZAI';

$lang->zai->configurationUnavailable = 'Configuration ZAI non disponible.';
$lang->zai->illegalZentaoUser        = 'Utilisateur Zentao illégal !';
$lang->zai->onlyPostRequest          = 'Cette opération ne prend en charge que les requêtes POST.';
$lang->zai->vectorizedAlreadyEnabled = 'La vectorisation des données est déjà activée.';
$lang->zai->vectorizedEnabled        = 'Vectorisation des données activée.';
$lang->zai->authenticationFailed     = 'Échec de l\'authentification !';
$lang->zai->syncRequestFailed        = 'Échec de la demande de synchronisation, veuillez réessayer plus tard';
$lang->zai->syncingHint              = 'Fermer cette page pendant la synchronisation mettra en pause le processus de synchronisation.';
$lang->zai->syncedWithFailedHint     = 'Certaines données de synchronisation ont échoué, veuillez réessayer plus tard';
$lang->zai->cannotFindMemoryInZai    = 'Impossible de trouver la base de connaissances avec la clé spécifiée dans ZAI, veuillez réinitialiser la cible de synchronisation.';
$lang->zai->confirmResetSync         = 'Voulez-vous réinitialiser l\'état de synchronisation ? Cela créera une nouvelle base de connaissances dans ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Vectorisation des Données Zentao';
$lang->zai->vectorized                = 'Vectorisation des Données';
$lang->zai->vectorizedIntro           = 'La vectorisation des données convertira les données générées dans le système Zentao en vecteurs pour référence dans les conversations IA, permettant à l\'IA de répondre aux questions plus précisément.';
$lang->zai->vectorizedUnavailableHint = 'Veuillez d\'abord configurer l\'application ZAI et vous assurer que le service ZAI est disponible.';
$lang->zai->callZaiAPIFailed          = 'Échec de l\'appel à l\'API ZAI (%s) : %s';

$lang->zai->vectorizedStatus = 'État';
$lang->zai->syncProgress     = 'Progrès de Synchronisation';
$lang->zai->syncingType      = 'Type de Synchronisation';
$lang->zai->finished         = 'Terminé';
$lang->zai->failed           = 'Échoué';
$lang->zai->totalSync        = 'Total';
$lang->zai->lastSyncTime     = 'Dernière Synchronisation';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Activer la Vectorisation des Données';
$lang->zai->syncActions->startSync  = 'Démarrer la Synchronisation';
$lang->zai->syncActions->resync     = 'Resynchroniser';
$lang->zai->syncActions->pauseSync  = 'Suspendre la Synchronisation';
$lang->zai->syncActions->resumeSync = 'Reprendre la Synchronisation';
$lang->zai->syncActions->resetSync  = 'Réinitialiser la Synchronisation';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Histoire';
$lang->zai->syncingTypeList['demand']   = 'Demande';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Document';
$lang->zai->syncingTypeList['design']   = 'Conception';
$lang->zai->syncingTypeList['feedback'] = 'Commentaire';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Non disponible';   // <== État persistant
$lang->zai->vectorizedStatusList['disabled']    = 'Désactivé';        // <== État persistant
$lang->zai->vectorizedStatusList['wait']        = 'En attente';       // <== État persistant
$lang->zai->vectorizedStatusList['syncing']     = 'Synchronisation';  // <== État persistant
$lang->zai->vectorizedStatusList['paused']      = 'En pause';
$lang->zai->vectorizedStatusList['synced']      = 'Synchronisé';      // <== État persistant
$lang->zai->vectorizedStatusList['failed']      = 'Échec de Synchronisation';

$vectorizedPanelLang = new \stdClass();
$vectorizedPanelLang->vectorized           = $lang->zai->vectorized;
$vectorizedPanelLang->vectorizedIntro      = $lang->zai->vectorizedIntro;
$vectorizedPanelLang->vectorizedStatus     = $lang->zai->vectorizedStatus;
$vectorizedPanelLang->syncProgress         = $lang->zai->syncProgress;
$vectorizedPanelLang->syncingType          = $lang->zai->syncingType;
$vectorizedPanelLang->finished             = $lang->zai->finished;
$vectorizedPanelLang->failed               = $lang->zai->failed;
$vectorizedPanelLang->syncActions          = $lang->zai->syncActions;
$vectorizedPanelLang->syncingTypeList      = $lang->zai->syncingTypeList;
$vectorizedPanelLang->vectorizedStatusList = $lang->zai->vectorizedStatusList;
$vectorizedPanelLang->syncRequestFailed    = $lang->zai->syncRequestFailed;
$vectorizedPanelLang->confirmResetSync     = $lang->zai->confirmResetSync;

$lang->zai->vectorizedPanelLang = $vectorizedPanelLang;
