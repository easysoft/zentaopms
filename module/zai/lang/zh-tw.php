<?php
$lang->zai->setting    = 'ZAI 設定';
$lang->zai->appID      = '應用集成ID';
$lang->zai->host       = '主機';
$lang->zai->port       = '端口';
$lang->zai->token      = '應用密鑰';
$lang->zai->adminToken = '管理密鑰';
$lang->zai->addSetting = '添加 ZAI 設定';

$lang->zai->configurationUnavailable = 'ZAI 設定不可用。';
$lang->zai->illegalZentaoUser        = '非法禪道用戶！';
$lang->zai->onlyPostRequest          = '此操作只支持 POST 請求。';
$lang->zai->vectorizedAlreadyEnabled = '數據向量化已經啟用。';
$lang->zai->vectorizedEnabled        = '數據向量化已啟用。';
$lang->zai->authenticationFailed     = '認證失敗！';
$lang->zai->syncRequestFailed        = '同步請求失敗，請稍後再試';
$lang->zai->syncingHint              = '同步過程中，關閉此頁面將會暫停同步。';
$lang->zai->syncedWithFailedHint     = '一些數據同步失敗，請稍後再試';
$lang->zai->cannotFindMemoryInZai    = '無法在 ZAI 中找到指定 key 的知識庫，請重置同步目標。';
$lang->zai->confirmResetSync         = '是否重置同步狀態，這將在 ZAI 中創建新的知識庫。';
$lang->zai->settingTips              = '請安裝<a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI服務</a>獲取金鑰。';

$lang->zai->zentaoVectorization       = '禪道數據向量化';
$lang->zai->vectorized                = '數據向量化';
$lang->zai->vectorizedIntro           = '數據向量化會將禪道系統內產生的數據進行向量化，以便於在 AI 對話中進行引用，讓 AI 可以更準確地回答問題。';
$lang->zai->vectorizedUnavailableHint = '請先設定 ZAI 應用，並確保 ZAI 服務可用。';
$lang->zai->callZaiAPIFailed          = '調用 ZAI API（%s）失敗：%s';

$lang->zai->vectorizedStatus = '狀態';
$lang->zai->syncProgress     = '同步進度';
$lang->zai->syncingType      = '同步類型';
$lang->zai->finished         = '已完成';
$lang->zai->failed           = '失敗';
$lang->zai->totalSync        = '總計';
$lang->zai->lastSyncTime     = '上次同步時間';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = '啟用數據向量化';
$lang->zai->syncActions->startSync  = '開始同步';
$lang->zai->syncActions->resync     = '重新同步';
$lang->zai->syncActions->pauseSync  = '暫停同步';
$lang->zai->syncActions->resumeSync = '繼續同步';
$lang->zai->syncActions->resetSync  = '重置同步';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = '需求';
$lang->zai->syncingTypeList['demand']   = '需求池需求';
$lang->zai->syncingTypeList['bug']      = 'BUG';
$lang->zai->syncingTypeList['doc']      = '文檔';
$lang->zai->syncingTypeList['design']   = '設計';
$lang->zai->syncingTypeList['feedback'] = '反饋';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = '不可用';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['disabled']    = '未啟用';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['wait']        = '等待同步';  // <== 持久化狀態
$lang->zai->vectorizedStatusList['syncing']     = '同步中';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['paused']      = '已暫停';
$lang->zai->vectorizedStatusList['synced']      = '已同步';   // <== 持久化狀態
$lang->zai->vectorizedStatusList['failed']      = '同步失敗';

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
