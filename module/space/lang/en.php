<?php
global $config;

$lang->space->common          = 'Applications';
$lang->space->browse          = 'Application List';
$lang->space->getStoreAppInfo = 'Get application information';
$lang->space->status          = 'Status';
$lang->space->noApps          = 'No service';
$lang->space->defaultSpace    = 'Default space';
$lang->space->systemSpace     = 'System space';
$lang->space->searchInstance  = 'Search Services';
$lang->space->upgrade         = 'Upgrade';
$lang->space->install         = 'Add an application';
$lang->space->createdBy       = 'Creator';
$lang->space->createdAt       = 'Creation time';
$lang->space->handConfig      = 'Manual configuration';
$lang->space->addType         = 'Add method';
$lang->space->instanceType    = 'Instance type';

$lang->space->notice =  new stdclass;
$lang->space->notice->toInstall = 'Please go to the application market to install';

$lang->space->byList = 'List';
$lang->space->byCard = 'Card';

$lang->space->featureBar['browse']['all'] = 'All';
if($config->inQuickon) $lang->space->featureBar['browse']['running']  = 'Running';
if($config->inQuickon) $lang->space->featureBar['browse']['stopped']  = 'Stopped';
if($config->inQuickon) $lang->space->featureBar['browse']['abnormal'] = 'Abnormal';

$lang->space->appType['gitlab']    = 'GitLab';
$lang->space->appType['gitea']     = 'Gitea';
$lang->space->appType['gogs']      = 'Gogs';
$lang->space->appType['jenkins']   = 'Jenkins';
$lang->space->appType['sonarqube'] = 'SonarQube';
$lang->space->appType['nexus']     = 'Nexus';
