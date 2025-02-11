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
