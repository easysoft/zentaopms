<?php
global $lang,$app;
$isEn = $app->getClientLang() == 'en';
$config->stakeholder->dtable = new stdclass();
$config->stakeholder->dtable->fieldList['id']['title'] = $lang->idAB;
$config->stakeholder->dtable->fieldList['id']['type']  = 'id';
$config->stakeholder->dtable->fieldList['id']['group'] = 1;

$config->stakeholder->dtable->fieldList['name']['title'] = $lang->stakeholder->name;
$config->stakeholder->dtable->fieldList['name']['type']  = 'title';
$config->stakeholder->dtable->fieldList['name']['fixed'] = 'left';
$config->stakeholder->dtable->fieldList['name']['link']  = array('module' => 'stakeholder', 'method' => 'view', 'params' => "id={id}");
$config->stakeholder->dtable->fieldList['name']['group'] = 1;

$config->stakeholder->dtable->fieldList['from']['title']    = $lang->stakeholder->from;
$config->stakeholder->dtable->fieldList['from']['name']     = 'from';
$config->stakeholder->dtable->fieldList['from']['type']     = 'category';
$config->stakeholder->dtable->fieldList['from']['map']      = $lang->stakeholder->fromList;
$config->stakeholder->dtable->fieldList['from']['sortType'] = true;
$config->stakeholder->dtable->fieldList['from']['show']     = true;
$config->stakeholder->dtable->fieldList['from']['group']    = 2;
$config->stakeholder->dtable->fieldList['from']['width']    = '100px';
if($isEn) $config->stakeholder->dtable->fieldList['from']['width'] = '150px';

$config->stakeholder->dtable->fieldList['role']['title'] = $lang->stakeholder->role;
$config->stakeholder->dtable->fieldList['role']['name']  = 'role';
$config->stakeholder->dtable->fieldList['role']['type']  = 'text';
$config->stakeholder->dtable->fieldList['role']['show']  = true;
$config->stakeholder->dtable->fieldList['role']['group'] = 3;
$config->stakeholder->dtable->fieldList['role']['map']   = $lang->user->roleList;
if($isEn) $config->stakeholder->dtable->fieldList['role']['width'] = '150px';

$config->stakeholder->dtable->fieldList['key']['title']    = $lang->stakeholder->isKey;
$config->stakeholder->dtable->fieldList['key']['type']     = 'text';
$config->stakeholder->dtable->fieldList['key']['map']      = $lang->stakeholder->keyList;
$config->stakeholder->dtable->fieldList['key']['sortType'] = true;
$config->stakeholder->dtable->fieldList['key']['group']    = 4;
$config->stakeholder->dtable->fieldList['key']['width']    = '100px';
if($isEn) $config->stakeholder->dtable->fieldList['key']['width'] = '130px';

$config->stakeholder->dtable->fieldList['nature']['title'] = $lang->stakeholder->nature;
$config->stakeholder->dtable->fieldList['nature']['width'] = '160px';
$config->stakeholder->dtable->fieldList['nature']['type']  = 'html';
$config->stakeholder->dtable->fieldList['nature']['group'] = 5;

$config->stakeholder->dtable->fieldList['analysis']['title'] = $lang->stakeholder->analysis;
$config->stakeholder->dtable->fieldList['analysis']['width'] = '160px';
$config->stakeholder->dtable->fieldList['analysis']['type']  = 'html';
$config->stakeholder->dtable->fieldList['analysis']['group'] = 6;

$config->stakeholder->dtable->fieldList['strategy']['title'] = $lang->stakeholder->strategy;
$config->stakeholder->dtable->fieldList['strategy']['width'] = '160px';
$config->stakeholder->dtable->fieldList['strategy']['type']  = 'html';
$config->stakeholder->dtable->fieldList['strategy']['group'] = 7;

$config->stakeholder->dtable->fieldList['phone']['title'] = $lang->stakeholder->phone;
$config->stakeholder->dtable->fieldList['phone']['name']  = 'phone';
$config->stakeholder->dtable->fieldList['phone']['type']  = 'text';
$config->stakeholder->dtable->fieldList['phone']['show']  = true;
$config->stakeholder->dtable->fieldList['phone']['group'] = 8;

$config->stakeholder->dtable->fieldList['qq']['title'] = $lang->stakeholder->qq;
$config->stakeholder->dtable->fieldList['qq']['name']  = 'qq';
$config->stakeholder->dtable->fieldList['qq']['type']  = 'text';
$config->stakeholder->dtable->fieldList['qq']['show']  = true;
$config->stakeholder->dtable->fieldList['qq']['group'] = 9;

$config->stakeholder->dtable->fieldList['weixin']['title'] = $lang->stakeholder->weixin;
$config->stakeholder->dtable->fieldList['weixin']['name']  = 'weixin';
$config->stakeholder->dtable->fieldList['weixin']['type']  = 'text';
$config->stakeholder->dtable->fieldList['weixin']['show']  = true;
$config->stakeholder->dtable->fieldList['weixin']['group'] = 10;

$config->stakeholder->dtable->fieldList['email']['title'] = $lang->stakeholder->email;
$config->stakeholder->dtable->fieldList['email']['name']  = 'email';
$config->stakeholder->dtable->fieldList['email']['type']  = 'text';
$config->stakeholder->dtable->fieldList['email']['show']  = true;
$config->stakeholder->dtable->fieldList['email']['group'] = 11;

$config->stakeholder->dtable->fieldList['actions']['title']    = $lang->actions;
$config->stakeholder->dtable->fieldList['actions']['name']     = 'actions';
$config->stakeholder->dtable->fieldList['actions']['type']     = 'actions';
$config->stakeholder->dtable->fieldList['actions']['width']    = 'auto';
$config->stakeholder->dtable->fieldList['actions']['fixed']    = 'right';
$config->stakeholder->dtable->fieldList['actions']['list']     = $config->stakeholder->actionList;
$config->stakeholder->dtable->fieldList['actions']['menu']     = $config->stakeholder->menu ?? array_keys($config->stakeholder->actionList);
$config->stakeholder->dtable->fieldList['actions']['required'] = true;
$config->stakeholder->dtable->fieldList['actions']['group']    = 12;
