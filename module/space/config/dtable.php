<?php
global $lang, $app;
$app->loadLang('instance');
$app->loadLang('store');

$config->space->dtable = new stdclass();

$config->space->dtable->fieldList['name']['title'] = $lang->instance->name;
$config->space->dtable->fieldList['name']['type']  = 'title';
$config->space->dtable->fieldList['name']['link']  = helper::createLink('instance', 'view', "id={id}");

$config->space->dtable->fieldList['appName']['title'] = $lang->instance->appName;
$config->space->dtable->fieldList['appName']['type']  = 'text';
$config->space->dtable->fieldList['appName']['link']  = helper::createLink('store', 'appview', "id={appID}");

$config->space->dtable->fieldList['appVersion']['title'] = $lang->store->appVersion;
$config->space->dtable->fieldList['appVersion']['type']  = 'text';

$config->space->dtable->fieldList['status']['name']  = 'statusText';
$config->space->dtable->fieldList['status']['title'] = $lang->space->status;
$config->space->dtable->fieldList['status']['type']  = 'html';

$config->space->dtable->fieldList['actions']['type'] = 'actions';
$config->space->dtable->fieldList['actions']['menu'] = array('start', 'stop', 'uninstall', 'visit', 'upgrade');

$config->space->dtable->fieldList['actions']['list']['start']['icon'] = 'play';
$config->space->dtable->fieldList['actions']['list']['start']['hint'] = $lang->instance->start;

$config->space->dtable->fieldList['actions']['list']['stop']['icon'] = 'off';
$config->space->dtable->fieldList['actions']['list']['stop']['hint'] = $lang->instance->stop;

$config->space->dtable->fieldList['actions']['list']['uninstall']['icon'] = 'trash';
$config->space->dtable->fieldList['actions']['list']['uninstall']['hint'] = $lang->instance->uninstall;

$config->space->dtable->fieldList['actions']['list']['visit']['icon'] = 'menu-my';
$config->space->dtable->fieldList['actions']['list']['visit']['hint'] = $lang->instance->visit;

$config->space->dtable->fieldList['actions']['list']['upgrade']['icon'] = 'refresh';
$config->space->dtable->fieldList['actions']['list']['upgrade']['hint'] = $lang->space->upgrade;
$config->space->dtable->fieldList['actions']['list']['upgrade']['url']  = helper::createLink('instance', 'upgrade', "id={id}");
