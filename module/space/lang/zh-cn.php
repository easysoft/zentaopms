<?php
global $config;

$lang->space->common          = '服务';
$lang->space->browse          = '服务列表';
$lang->space->getStoreAppInfo = '获取服务信息';
$lang->space->status          = '状态';
$lang->space->noApps          = '暂无服务';
$lang->space->defaultSpace    = '默认空间';
$lang->space->systemSpace     = '系统空间';
$lang->space->searchInstance  = '搜索服务';
$lang->space->upgrade         = '升级';
$lang->space->install         = '添加服务';
$lang->space->createdBy       = '创建者';
$lang->space->createdAt       = '创建时间';
$lang->space->handConfig      = '手工配置';
$lang->space->addType         = '添加方式';
$lang->space->instanceType    = '实例类型';
$lang->space->monitorSetting  = '告警阈值';

$lang->space->monitor = new stdClass();
$lang->space->monitor->warning  = '普通告警';
$lang->space->monitor->danger   = '紧急告警';
$lang->space->monitor->cpu      = 'CPU负载';
$lang->space->monitor->memory   = '内存使用';
$lang->space->monitor->disk     = '硬盘占用';
$lang->space->monitor->used     = '占用';
$lang->space->monitor->over     = '超过';
$lang->space->monitor->duration = '持续';
$lang->space->monitor->minutes  = '分钟';
$lang->space->monitor->tips     = '%s超过%d%%。';
$lang->space->monitor->cpuTips  = '%s占用%d%%，持续%d分钟。';

$lang->space->monitor->error         = '百分数为1-100的整数。';
$lang->space->monitor->durationError = '持续时间为正整数。';
$lang->space->monitor->cneError      = '调用cne失败。';

$lang->space->notice =  new stdClass();
$lang->space->notice->toInstall = '请到应用市场安装';

$lang->space->byList = '列表';
$lang->space->byCard = '卡片';

$lang->space->featureBar['browse']['all'] = '全部';
if($config->inQuickon) $lang->space->featureBar['browse']['running']  = '运行中';
if($config->inQuickon) $lang->space->featureBar['browse']['stopped']  = '已关闭';
if($config->inQuickon) $lang->space->featureBar['browse']['abnormal'] = '异常';

$lang->space->appType['gitlab']    = 'GitLab';
$lang->space->appType['jenkins']   = 'Jenkins';
$lang->space->appType['sonarqube'] = 'SonarQube';
if(!$config->inQuickon)
{
    $lang->space->appType['gitea'] = 'Gitea';
    $lang->space->appType['gogs']  = 'Gogs';
}
