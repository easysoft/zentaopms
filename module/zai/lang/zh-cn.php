<?php
$lang->zai->setting    = 'ZAI 配置';
$lang->zai->appID      = '应用集成ID';
$lang->zai->host       = '主机';
$lang->zai->port       = '端口';
$lang->zai->token      = '应用密钥';
$lang->zai->adminToken = '管理密钥';
$lang->zai->addSetting = '添加 ZAI 配置';

$lang->zai->configurationUnavailable = 'ZAI 配置不可用。';
$lang->zai->illegalZentaoUser        = '非法禅道用户！';
$lang->zai->onlyPostRequest          = '此操作只支持 POST 请求。';
$lang->zai->vectorizedAlreadyEnabled = '数据向量化已经启用。';
$lang->zai->vectorizedEnabled        = '数据向量化已启用。';
$lang->zai->authenticationFailed     = '认证失败！';
$lang->zai->syncRequestFailed        = '同步请求失败，请稍后再试';
$lang->zai->syncingHint              = '同步过程中，关闭此页面将会暂停同步。';
$lang->zai->syncedWithFailedHint     = '一些数据同步失败，请稍后再试';
$lang->zai->cannotFindMemoryInZai    = '无法在 ZAI 中找到指定 key 的知识库，请重置同步目标。';
$lang->zai->confirmResetSync         = '是否重置同步状态，这将在 ZAI 中创建新的知识库。';
$lang->zai->settingTips              = '请安装<a class="btn btn-link text-primary px-1" style="text-decoration: none;" href="%s" target="_blank">ZAI服务</a>获取密钥。';

$lang->zai->zentaoVectorization       = '禅道数据向量化';
$lang->zai->vectorized                = '数据向量化';
$lang->zai->vectorizedIntro           = '数据向量化会将禅道系统内产生的数据进行向量化，以便于在 AI 对话中进行引用，让 AI 可以更准确地回答问题。';
$lang->zai->vectorizedUnavailableHint = '请先配置 ZAI 应用，并确保 ZAI 服务可用。';
$lang->zai->callZaiAPIFailed          = '调用 ZAI API（%s）失败：%s';

$lang->zai->vectorizedStatus = '状态';
$lang->zai->syncProgress     = '同步进度';
$lang->zai->syncingType      = '同步类型';
$lang->zai->finished         = '已完成';
$lang->zai->failed           = '失败';
$lang->zai->totalSync        = '总计';
$lang->zai->lastSyncTime     = '上次同步时间';

$lang->zai->syncActions = new stdClass();
$lang->zai->syncActions->enable     = '启用数据向量化';
$lang->zai->syncActions->startSync  = '开始同步';
$lang->zai->syncActions->resync     = '重新同步';
$lang->zai->syncActions->pauseSync  = '暂停同步';
$lang->zai->syncActions->resumeSync = '继续同步';
$lang->zai->syncActions->resetSync  = '重置同步';

$lang->zai->syncingTypeList = array();
$lang->zai->syncingTypeList['story']    = '需求';
$lang->zai->syncingTypeList['demand']   = '需求池需求';
$lang->zai->syncingTypeList['bug']      = 'BUG';
$lang->zai->syncingTypeList['doc']      = '文档';
$lang->zai->syncingTypeList['design']   = '设计';
$lang->zai->syncingTypeList['feedback'] = '反馈';

$lang->zai->vectorizedStatusList = array();
$lang->zai->vectorizedStatusList['unavailable'] = '不可用';   // <== 持久化状态
$lang->zai->vectorizedStatusList['disabled']    = '未启用';   // <== 持久化状态
$lang->zai->vectorizedStatusList['wait']        = '等待同步';  // <== 持久化状态
$lang->zai->vectorizedStatusList['syncing']     = '同步中';   // <== 持久化状态
$lang->zai->vectorizedStatusList['paused']      = '已暂停';
$lang->zai->vectorizedStatusList['synced']      = '已同步';   // <== 持久化状态
$lang->zai->vectorizedStatusList['failed']      = '同步失败';

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
