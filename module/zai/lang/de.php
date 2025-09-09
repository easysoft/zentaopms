<?php
$lang->zai->setting    = 'ZAI Einstellungen';
$lang->zai->appID      = 'App ID';
$lang->zai->host       = 'Host';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'App Schlüssel';
$lang->zai->adminToken = 'Admin Schlüssel';
$lang->zai->addSetting = 'ZAI Einstellungen hinzufügen';

$lang->zai->configurationUnavailable = 'ZAI Konfiguration nicht verfügbar.';
$lang->zai->illegalZentaoUser        = 'Ungültiger Zentao-Benutzer!';
$lang->zai->onlyPostRequest          = 'Diese Operation unterstützt nur POST-Anfragen.';
$lang->zai->vectorizedAlreadyEnabled = 'Datenvektorisierung ist bereits aktiviert.';
$lang->zai->vectorizedEnabled        = 'Datenvektorisierung aktiviert.';
$lang->zai->authenticationFailed     = 'Authentifizierung fehlgeschlagen!';
$lang->zai->syncRequestFailed        = 'Synchronisierungsanfrage fehlgeschlagen, bitte versuchen Sie es später erneut';
$lang->zai->syncingHint              = 'Das Schließen dieser Seite während der Synchronisierung pausiert den Synchronisierungsprozess.';
$lang->zai->syncedWithFailedHint     = 'Einige Datensynchronisierungen sind fehlgeschlagen, bitte versuchen Sie es später erneut';
$lang->zai->cannotFindMemoryInZai    = 'Kann Wissensdatenbank mit angegebenem Schlüssel in ZAI nicht finden, bitte setzen Sie das Synchronisierungsziel zurück.';
$lang->zai->confirmResetSync         = 'Möchten Sie den Synchronisierungsstatus zurücksetzen? Dies erstellt eine neue Wissensdatenbank in ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primaty px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Zentao Datenvektorisierung';
$lang->zai->vectorized                = 'Datenvektorisierung';
$lang->zai->vectorizedIntro           = 'Die Datenvektorisierung konvertiert im Zentao-System generierte Daten in Vektoren zur Referenz in KI-Gesprächen, wodurch die KI Fragen genauer beantworten kann.';
$lang->zai->vectorizedUnavailableHint = 'Bitte konfigurieren Sie zuerst die ZAI-Anwendung und stellen Sie sicher, dass der ZAI-Service verfügbar ist.';
$lang->zai->callZaiAPIFailed          = 'Aufruf der ZAI API (%s) fehlgeschlagen: %s';

$lang->zai->vectorizedStatus = 'Status';
$lang->zai->syncProgress     = 'Synchronisierungsfortschritt';
$lang->zai->syncingType      = 'Synchronisierungstyp';
$lang->zai->finished         = 'Abgeschlossen';
$lang->zai->failed           = 'Fehlgeschlagen';
$lang->zai->totalSync        = 'Gesamt';
$lang->zai->lastSyncTime     = 'Letzte Synchronisierung';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Datenvektorisierung aktivieren';
$lang->zai->syncActions->startSync  = 'Synchronisierung starten';
$lang->zai->syncActions->resync     = 'Neu synchronisieren';
$lang->zai->syncActions->pauseSync  = 'Synchronisierung pausieren';
$lang->zai->syncActions->resumeSync = 'Synchronisierung fortsetzen';
$lang->zai->syncActions->resetSync  = 'Synchronisierung zurücksetzen';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Story';
$lang->zai->syncingTypeList['demand']   = 'Anforderung';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Dokument';
$lang->zai->syncingTypeList['design']   = 'Design';
$lang->zai->syncingTypeList['feedback'] = 'Feedback';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Nicht verfügbar';    // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['disabled']    = 'Deaktiviert';        // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['wait']        = 'Warten auf Sync';    // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['syncing']     = 'Synchronisierung';   // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['paused']      = 'Pausiert';
$lang->zai->vectorizedStatusList['synced']      = 'Synchronisiert';     // <== Persistenter Zustand
$lang->zai->vectorizedStatusList['failed']      = 'Synchronisierung fehlgeschlagen';

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
