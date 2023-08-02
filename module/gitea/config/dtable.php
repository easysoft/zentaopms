<?php
global $lang, $app;
$app->loadLang('gitea');

$config->gitea->dtable = new stdclass();

$config->gitea->dtable->fieldList['id']['title']    = 'ID';
$config->gitea->dtable->fieldList['id']['name']     = 'id';
$config->gitea->dtable->fieldList['id']['type']     = 'number';
$config->gitea->dtable->fieldList['id']['sortType'] = 'desc';
$config->gitea->dtable->fieldList['id']['checkbox'] = false;
$config->gitea->dtable->fieldList['id']['width']    = '80';

$config->gitea->dtable->fieldList['name']['title']    = $lang->gitea->name;
$config->gitea->dtable->fieldList['name']['name']     = 'name';
$config->gitea->dtable->fieldList['name']['type']     = 'desc';
$config->gitea->dtable->fieldList['name']['sortType'] = true;
$config->gitea->dtable->fieldList['name']['hint']     = true;
$config->gitea->dtable->fieldList['name']['minWidth'] = '356';

$config->gitea->dtable->fieldList['url']['title']    = $lang->gitea->url;
$config->gitea->dtable->fieldList['url']['name']     = 'url';
$config->gitea->dtable->fieldList['url']['type']     = 'desc';
$config->gitea->dtable->fieldList['url']['sortType'] = true;
$config->gitea->dtable->fieldList['url']['hint']     = true;
$config->gitea->dtable->fieldList['url']['minWidth'] = '356';

$config->gitea->actionList = array();
$config->gitea->actionList['edit']['icon'] = 'edit';
$config->gitea->actionList['edit']['text'] = $lang->gitea->edit;
$config->gitea->actionList['edit']['hint'] = $lang->gitea->edit;
$config->gitea->actionList['edit']['url']  = helper::createLink('gitea', 'edit',"giteaID={id}");

$config->gitea->actionList['bindUser']['icon'] = 'lock';
$config->gitea->actionList['bindUser']['text'] = $lang->gitea->bindUser;
$config->gitea->actionList['bindUser']['hint'] = $lang->gitea->bindUser;
$config->gitea->actionList['bindUser']['url']  = helper::createLink('gitea', 'bindUser',"giteaID={id}");

$config->gitea->actionList['delete']['icon']       = 'trash';
$config->gitea->actionList['delete']['text']       = $lang->gitea->delete;
$config->gitea->actionList['delete']['hint']       = $lang->gitea->delete;
$config->gitea->actionList['delete']['ajaxSubmit'] = true;
$config->gitea->actionList['delete']['url']        = helper::createLink('gitea', 'delete',"giteaID={id}");

$config->gitea->dtable->fieldList['actions']['name']     = 'actions';
$config->gitea->dtable->fieldList['actions']['title']    = $lang->actions;
$config->gitea->dtable->fieldList['actions']['type']     = 'actions';
$config->gitea->dtable->fieldList['actions']['width']    = '160';
$config->gitea->dtable->fieldList['actions']['sortType'] = false;
$config->gitea->dtable->fieldList['actions']['fixed']    = 'right';
$config->gitea->dtable->fieldList['actions']['menu']     = array('edit', 'bindUser', 'delete');
$config->gitea->dtable->fieldList['actions']['list']     = $config->gitea->actionList;
