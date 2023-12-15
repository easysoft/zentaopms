<?php
global $lang, $app;
$app->loadLang('instance');
$app->loadLang('store');
$app->loadLang('task');
$app->loadLang('gitlab');

$config->space->dtable = new stdclass();

$config->space->dtable->fieldList['name']['title'] = $lang->instance->name;
$config->space->dtable->fieldList['name']['type']  = 'title';

$config->space->dtable->fieldList['appName']['title']    = $lang->instance->appName;
$config->space->dtable->fieldList['appName']['type']     = 'text';
$config->space->dtable->fieldList['appName']['width']    = '90';
$config->space->dtable->fieldList['appName']['sortType'] = true;

if($config->inQuickon)
{
    $config->space->dtable->fieldList['status']['name']     = 'status';
    $config->space->dtable->fieldList['status']['title']    = $lang->space->status;
    $config->space->dtable->fieldList['status']['type']     = 'category';
    $config->space->dtable->fieldList['status']['map']      = $lang->instance->statusList;
    $config->space->dtable->fieldList['status']['group']    = 'status';
    $config->space->dtable->fieldList['status']['width']    = '80';
    $config->space->dtable->fieldList['status']['sortType'] = true;

    $config->space->dtable->fieldList['appVersion']['title'] = $lang->store->appVersion;
    $config->space->dtable->fieldList['appVersion']['type']  = 'text';
    $config->space->dtable->fieldList['appVersion']['group'] = 'version';
    $config->space->dtable->fieldList['appVersion']['width'] = '136';
}

$config->space->dtable->fieldList['createdBy']['title'] = $lang->space->createdBy;
$config->space->dtable->fieldList['createdBy']['type']  = 'user';
$config->space->dtable->fieldList['createdBy']['group'] = 'created';

$config->space->dtable->fieldList['createdAt']['title'] = $lang->space->createdAt;
$config->space->dtable->fieldList['createdAt']['type']  = 'datetime';
$config->space->dtable->fieldList['createdAt']['group'] = 'created';

$config->space->dtable->fieldList['actions']['type'] = 'actions';
$config->space->dtable->fieldList['actions']['menu'] = array('visit', 'ajaxStart|ajaxStop', 'edit', 'bindUser', 'ajaxUninstall', 'upgrade');
if(!$config->inQuickon) $config->space->dtable->fieldList['actions']['menu'] = array('visit', 'edit', 'bindUser', 'ajaxUninstall');

$config->space->dtable->fieldList['actions']['list']['ajaxStart']['icon']      = 'play';
$config->space->dtable->fieldList['actions']['list']['ajaxStart']['className'] = 'ajax-submit';
$config->space->dtable->fieldList['actions']['list']['ajaxStart']['hint']      = $lang->instance->start;
$config->space->dtable->fieldList['actions']['list']['ajaxStart']['url']       = array('module' => 'instance', 'method' => 'ajaxStart', 'params' => "id={id}");

$config->space->dtable->fieldList['actions']['list']['ajaxStop']['icon']         = 'off';
$config->space->dtable->fieldList['actions']['list']['ajaxStop']['className']    = 'ajax-submit';
$config->space->dtable->fieldList['actions']['list']['ajaxStop']['hint']         = $lang->instance->stop;
$config->space->dtable->fieldList['actions']['list']['ajaxStop']['url']          = array('module' => 'instance', 'method' => 'ajaxStop', 'params' => "id={id}");
$config->space->dtable->fieldList['actions']['list']['ajaxStop']['data-confirm'] = $lang->instance->notices['confirmStop'];

$config->space->dtable->fieldList['actions']['list']['ajaxUninstall']['icon']         = 'trash';
$config->space->dtable->fieldList['actions']['list']['ajaxUninstall']['hint']         = $lang->instance->uninstall;
$config->space->dtable->fieldList['actions']['list']['ajaxUninstall']['className']    = 'ajax-submit';
$config->space->dtable->fieldList['actions']['list']['ajaxUninstall']['data-confirm'] = $lang->instance->notices['confirmUninstall'];
$config->space->dtable->fieldList['actions']['list']['ajaxUninstall']['url']          = array('module' => 'instance', 'method' => 'ajaxUninstall', 'params' => 'id={orgID}&type={type}');

$config->space->dtable->fieldList['actions']['list']['visit']['icon']      = 'menu-my';
$config->space->dtable->fieldList['actions']['list']['visit']['hint']      = $lang->instance->visit;
$config->space->dtable->fieldList['actions']['list']['visit']['url']       = array('module' => 'instance', 'method' => 'visit', 'params' => 'id={id}&externalID={externalID}');
$config->space->dtable->fieldList['actions']['list']['visit']['className'] = 'ajax-submit';

$config->space->dtable->fieldList['actions']['list']['upgrade']['icon']        = 'refresh';
$config->space->dtable->fieldList['actions']['list']['upgrade']['data-toggle'] = 'modal';
$config->space->dtable->fieldList['actions']['list']['upgrade']['data-size']   = 'sm';
$config->space->dtable->fieldList['actions']['list']['upgrade']['hint']        = $lang->space->upgrade;
$config->space->dtable->fieldList['actions']['list']['upgrade']['url']         = array('module' => 'instance', 'method' => 'upgrade', 'params' => "id={id}");

$config->space->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->space->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->edit;
$config->space->dtable->fieldList['actions']['list']['edit']['url']  = 'javascript:editApp({orgID}, "{appName}")';

$config->space->dtable->fieldList['actions']['list']['bindUser']['icon'] = 'lock';
$config->space->dtable->fieldList['actions']['list']['bindUser']['hint'] = $lang->gitlab->bindUser;
$config->space->dtable->fieldList['actions']['list']['bindUser']['url']  = 'javascript:bindUser({externalID}, "{appName}")';
