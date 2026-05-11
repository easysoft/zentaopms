<?php
global $config;

$lang->space->common          = 'Services';
$lang->space->browse          = 'Service List';
$lang->space->getStoreAppInfo = 'Fetch Service Details';
$lang->space->status          = 'Status';
$lang->space->noApps          = 'No Services Found';
$lang->space->defaultSpace    = 'Default space';
$lang->space->systemSpace     = 'System space';
$lang->space->searchInstance  = 'Search Services';
$lang->space->upgrade         = 'Upgrade';
$lang->space->install         = 'Add service';
$lang->space->createdBy       = 'Creator';
$lang->space->createdAt       = 'Created At';
$lang->space->handConfig      = 'Manual Config';
$lang->space->addType         = 'Addition Method';
$lang->space->instanceType    = 'Instance type';

$lang->space->notice =  new stdClass();
$lang->space->notice->toInstall = 'Install via App Marketplace';

$lang->space->byList = 'List View';
$lang->space->byCard = 'Card View';

$lang->space->featureBar['browse']['all'] = 'All';
if($config->inQuickon) $lang->space->featureBar['browse']['running']  = 'Running';
if($config->inQuickon) $lang->space->featureBar['browse']['stopped']  = 'Stopped';
if($config->inQuickon) $lang->space->featureBar['browse']['abnormal'] = 'Abnormal';

$lang->space->appType['gitlab']    = 'GitLab';
$lang->space->appType['jenkins']   = 'Jenkins';
$lang->space->appType['sonarqube'] = 'SonarQube';
if(!$config->inQuickon && !$config->inCompose)
{
    $lang->space->appType['gitea'] = 'Gitea';
    $lang->space->appType['gogs']  = 'Gogs';
}
