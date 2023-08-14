<?php
global $lang, $app;
$app->loadLang('gogs');

$config->gogs->dtable = new stdclass();

$config->gogs->dtable->fieldList['id']['title']    = 'ID';
$config->gogs->dtable->fieldList['id']['name']     = 'id';
$config->gogs->dtable->fieldList['id']['type']     = 'number';
$config->gogs->dtable->fieldList['id']['sortType'] = true;
$config->gogs->dtable->fieldList['id']['checkbox'] = false;
$config->gogs->dtable->fieldList['id']['width']    = '80';

$config->gogs->dtable->fieldList['name']['title']    = $lang->gogs->name;
$config->gogs->dtable->fieldList['name']['name']     = 'name';
$config->gogs->dtable->fieldList['name']['type']     = 'text';
$config->gogs->dtable->fieldList['name']['sortType'] = true;
$config->gogs->dtable->fieldList['name']['hint']     = true;
$config->gogs->dtable->fieldList['name']['minWidth'] = '356';

$config->gogs->dtable->fieldList['url']['title']    = $lang->gogs->url;
$config->gogs->dtable->fieldList['url']['name']     = 'url';
$config->gogs->dtable->fieldList['url']['type']     = 'text';
$config->gogs->dtable->fieldList['url']['sortType'] = true;
$config->gogs->dtable->fieldList['url']['hint']     = true;
$config->gogs->dtable->fieldList['url']['minWidth'] = '356';

$config->gogs->actionList = array();
$config->gogs->actionList['edit']['icon'] = 'edit';
$config->gogs->actionList['edit']['text'] = $lang->gogs->edit;
$config->gogs->actionList['edit']['hint'] = $lang->gogs->edit;
$config->gogs->actionList['edit']['url']  = helper::createLink('gogs', 'edit',"gogsID={id}");

$config->gogs->actionList['bindUser']['icon'] = 'lock';
$config->gogs->actionList['bindUser']['text'] = $lang->gogs->bindUser;
$config->gogs->actionList['bindUser']['hint'] = $lang->gogs->bindUser;
$config->gogs->actionList['bindUser']['url']  = helper::createLink('gogs', 'bindUser',"gogsID={id}");

$config->gogs->actionList['delete']['icon']       = 'trash';
$config->gogs->actionList['delete']['text']       = $lang->gogs->delete;
$config->gogs->actionList['delete']['hint']       = $lang->gogs->delete;
$config->gogs->actionList['delete']['ajaxSubmit'] = true;
$config->gogs->actionList['delete']['url']        = helper::createLink('gogs', 'delete',"gogsID={id}");

$config->gogs->dtable->fieldList['actions']['name']     = 'actions';
$config->gogs->dtable->fieldList['actions']['title']    = $lang->actions;
$config->gogs->dtable->fieldList['actions']['type']     = 'actions';
$config->gogs->dtable->fieldList['actions']['width']    = '160';
$config->gogs->dtable->fieldList['actions']['sortType'] = false;
$config->gogs->dtable->fieldList['actions']['fixed']    = 'right';
$config->gogs->dtable->fieldList['actions']['menu']     = array('edit', 'bindUser', 'delete');
$config->gogs->dtable->fieldList['actions']['list']     = $config->gogs->actionList;

$config->gogs->dtable->bindUser = new stdclass();
$config->gogs->dtable->bindUser->fieldList['gogsUser']['title']    = $lang->gogs->gogsAccount;
$config->gogs->dtable->bindUser->fieldList['gogsUser']['type']     = 'avatarName';
$config->gogs->dtable->bindUser->fieldList['gogsUser']['sortType'] = false;
$config->gogs->dtable->bindUser->fieldList['gogsUser']['width']    = 300;

$config->gogs->dtable->bindUser->fieldList['gogsEmail']['title'] = $lang->gogs->gogsEmail;
$config->gogs->dtable->bindUser->fieldList['gogsEmail']['type']  = 'text';
$config->gogs->dtable->bindUser->fieldList['gogsEmail']['width'] = 200;

$config->gogs->dtable->bindUser->fieldList['email']['title'] = $lang->gogs->zentaoEmail;
$config->gogs->dtable->bindUser->fieldList['email']['type']  = 'text';
$config->gogs->dtable->bindUser->fieldList['email']['width'] = 200;

$config->gogs->dtable->bindUser->fieldList['zentaoUsers']['title']   = array('html' => $lang->gogs->zentaoAccount . "<span class='text-gray'>{$lang->gogs->accountDesc}</span>");
$config->gogs->dtable->bindUser->fieldList['zentaoUsers']['type']    = 'control';
$config->gogs->dtable->bindUser->fieldList['zentaoUsers']['control'] = 'picker';
$config->gogs->dtable->bindUser->fieldList['zentaoUsers']['width']   = 300;

$config->gogs->dtable->bindUser->fieldList['status']['title'] = $lang->gogs->bindingStatus;
$config->gogs->dtable->bindUser->fieldList['status']['type']  = 'html';
$config->gogs->dtable->bindUser->fieldList['status']['width'] = 100;
$config->gogs->dtable->bindUser->fieldList['status']['map']   = $lang->gogs->bindStatus;
