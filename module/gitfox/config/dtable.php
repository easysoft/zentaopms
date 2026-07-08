<?php
global $lang, $app;
$app->loadLang('gitfox');

$config->gitfox->dtable = new stdclass();

$config->gitfox->dtable->fieldList['id']['title']    = 'ID';
$config->gitfox->dtable->fieldList['id']['name']     = 'id';
$config->gitfox->dtable->fieldList['id']['type']     = 'number';
$config->gitfox->dtable->fieldList['id']['sortType'] = 'desc';
$config->gitfox->dtable->fieldList['id']['checkbox'] = false;
$config->gitfox->dtable->fieldList['id']['width']    = '80';

$config->gitfox->dtable->fieldList['name']['title']    = $lang->gitfox->name;
$config->gitfox->dtable->fieldList['name']['name']     = 'name';
$config->gitfox->dtable->fieldList['name']['type']     = 'desc';
$config->gitfox->dtable->fieldList['name']['sortType'] = true;
$config->gitfox->dtable->fieldList['name']['hint']     = true;
$config->gitfox->dtable->fieldList['name']['minWidth'] = '356';

$config->gitfox->dtable->fieldList['url']['title']    = $lang->gitfox->url;
$config->gitfox->dtable->fieldList['url']['name']     = 'url';
$config->gitfox->dtable->fieldList['url']['type']     = 'desc';
$config->gitfox->dtable->fieldList['url']['sortType'] = true;
$config->gitfox->dtable->fieldList['url']['hint']     = true;
$config->gitfox->dtable->fieldList['url']['minWidth'] = '356';

$config->gitfox->actionList = array();
$config->gitfox->actionList['edit']['icon'] = 'edit';
$config->gitfox->actionList['edit']['text'] = $lang->gitfox->edit;
$config->gitfox->actionList['edit']['hint'] = $lang->gitfox->edit;
$config->gitfox->actionList['edit']['url']  = helper::createLink('gitfox', 'edit',"gitfoxID={id}");

$config->gitfox->actionList['bindUser']['icon'] = 'lock';
$config->gitfox->actionList['bindUser']['text'] = $lang->gitfox->bindUser;
$config->gitfox->actionList['bindUser']['hint'] = $lang->gitfox->bindUser;
$config->gitfox->actionList['bindUser']['url']  = helper::createLink('gitfox', 'bindUser',"gitfoxID={id}");

$config->gitfox->actionList['delete']['icon']       = 'trash';
$config->gitfox->actionList['delete']['text']       = $lang->gitfox->delete;
$config->gitfox->actionList['delete']['hint']       = $lang->gitfox->delete;
$config->gitfox->actionList['delete']['ajaxSubmit'] = true;
$config->gitfox->actionList['delete']['url']        = helper::createLink('gitfox', 'delete',"gitfoxID={id}");

$config->gitfox->dtable->fieldList['actions']['name']     = 'actions';
$config->gitfox->dtable->fieldList['actions']['title']    = $lang->actions;
$config->gitfox->dtable->fieldList['actions']['type']     = 'actions';
$config->gitfox->dtable->fieldList['actions']['width']    = '160';
$config->gitfox->dtable->fieldList['actions']['sortType'] = false;
$config->gitfox->dtable->fieldList['actions']['fixed']    = 'right';
$config->gitfox->dtable->fieldList['actions']['menu']     = array('edit', 'bindUser', 'delete');
$config->gitfox->dtable->fieldList['actions']['list']     = $config->gitfox->actionList;

$config->gitfox->dtable->bindUser = new stdclass();
$config->gitfox->dtable->bindUser->fieldList['gitfoxUser']['title']    = $lang->gitfox->gitfoxAccount;
$config->gitfox->dtable->bindUser->fieldList['gitfoxUser']['type']     = 'avatarName';
$config->gitfox->dtable->bindUser->fieldList['gitfoxUser']['sortType'] = false;
$config->gitfox->dtable->bindUser->fieldList['gitfoxUser']['width']    = 300;

$config->gitfox->dtable->bindUser->fieldList['gitfoxEmail']['title'] = $lang->gitfox->gitfoxEmail;
$config->gitfox->dtable->bindUser->fieldList['gitfoxEmail']['type']  = 'text';
$config->gitfox->dtable->bindUser->fieldList['gitfoxEmail']['width'] = 200;

$config->gitfox->dtable->bindUser->fieldList['email']['title'] = $lang->gitfox->zentaoEmail;
$config->gitfox->dtable->bindUser->fieldList['email']['type']  = 'text';
$config->gitfox->dtable->bindUser->fieldList['email']['width'] = 200;

$config->gitfox->dtable->bindUser->fieldList['zentaoUsers']['title']   = array('html' => $lang->gitfox->zentaoAccount . "<span class='text-gray'>{$lang->gitfox->accountDesc}</span>");
$config->gitfox->dtable->bindUser->fieldList['zentaoUsers']['type']    = 'control';
$config->gitfox->dtable->bindUser->fieldList['zentaoUsers']['control'] = 'picker';
$config->gitfox->dtable->bindUser->fieldList['zentaoUsers']['width']   = 300;

$config->gitfox->dtable->bindUser->fieldList['status']['title'] = $lang->gitfox->bindingStatus;
$config->gitfox->dtable->bindUser->fieldList['status']['html']  = true;
$config->gitfox->dtable->bindUser->fieldList['status']['width'] = 100;
$config->gitfox->dtable->bindUser->fieldList['status']['map']   = $lang->gitfox->bindStatus;
