<?php
$lang->zai->setting    = 'ZAI Setting';
$lang->zai->appID      = 'App ID';
$lang->zai->host       = 'Host';
$lang->zai->port       = 'Port';
$lang->zai->token      = 'App Secret';
$lang->zai->adminToken = 'Admin Secret';
$lang->zai->addSetting = 'Add ZAI Setting';

$lang->zai->configurationUnavailable = 'ZAI configuration unavailable.';
$lang->zai->illegalZentaoUser        = 'Illegal Zentao user!';
$lang->zai->onlyPostRequest          = 'This operation only supports POST requests.';
$lang->zai->vectorizedAlreadyEnabled = 'Data vectorization is already enabled.';
$lang->zai->vectorizedEnabled        = 'Data vectorization enabled.';
$lang->zai->authenticationFailed     = 'Authentication failed!';
$lang->zai->syncRequestFailed        = 'Sync request failed, please try again later';
$lang->zai->syncingHint              = 'Closing this page during sync will pause the sync process.';
$lang->zai->syncedWithFailedHint     = 'Some data sync failed, please try again later';
$lang->zai->cannotFindMemoryInZai    = 'Cannot find knowledge base with specified key in ZAI, please reset sync target.';
$lang->zai->confirmResetSync         = 'Do you want to reset sync status? This will create a new knowledge base in ZAI.';
$lang->zai->settingTips              = 'Please install <a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI service</a> to get the key.';

$lang->zai->zentaoVectorization       = 'Zentao Data Vectorization';
$lang->zai->vectorized                = 'Data Vectorization';
$lang->zai->vectorizedIntro           = 'Data vectorization will convert data generated in the Zentao system into vectors for reference in AI conversations, allowing AI to answer questions more accurately.';
$lang->zai->vectorizedUnavailableHint = 'Please configure ZAI application first and ensure ZAI service is available.';
$lang->zai->callZaiAPIFailed          = 'Failed to call ZAI API (%s): %s';

$lang->zai->vectorizedStatus = 'Status';
$lang->zai->syncProgress     = 'Sync Progress';
$lang->zai->syncingType      = 'Sync Type';
$lang->zai->finished         = 'Finished';
$lang->zai->failed           = 'Failed';
$lang->zai->totalSync        = 'Total';
$lang->zai->lastSyncTime     = 'Last Sync Time';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = 'Enable Data Vectorization';
$lang->zai->syncActions->startSync  = 'Start Sync';
$lang->zai->syncActions->resync     = 'Resync';
$lang->zai->syncActions->pauseSync  = 'Pause Sync';
$lang->zai->syncActions->resumeSync = 'Resume Sync';
$lang->zai->syncActions->resetSync  = 'Reset Sync';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = 'Story';
$lang->zai->syncingTypeList['demand']   = 'Demand';
$lang->zai->syncingTypeList['bug']      = 'Bug';
$lang->zai->syncingTypeList['doc']      = 'Document';
$lang->zai->syncingTypeList['design']   = 'Design';
$lang->zai->syncingTypeList['feedback'] = 'Feedback';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = 'Unavailable';   // <== Persistent state
$lang->zai->vectorizedStatusList['disabled']    = 'Disabled';      // <== Persistent state
$lang->zai->vectorizedStatusList['wait']        = 'Waiting Sync';  // <== Persistent state
$lang->zai->vectorizedStatusList['syncing']     = 'Syncing';       // <== Persistent state
$lang->zai->vectorizedStatusList['paused']      = 'Paused';
$lang->zai->vectorizedStatusList['synced']      = 'Synced';        // <== Persistent state
$lang->zai->vectorizedStatusList['failed']      = 'Sync Failed';

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
