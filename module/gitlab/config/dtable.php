<?php
global $lang, $app;
$app->loadLang('gitlab');

$config->gitlab->dtable = new stdclass();

$config->gitlab->dtable->fieldList['id']['title']    = 'ID';
$config->gitlab->dtable->fieldList['id']['name']     = 'id';
$config->gitlab->dtable->fieldList['id']['type']     = 'number';
$config->gitlab->dtable->fieldList['id']['sortType'] = 'desc';
$config->gitlab->dtable->fieldList['id']['checkbox'] = false;
$config->gitlab->dtable->fieldList['id']['width']    = '80';

$config->gitlab->dtable->fieldList['name']['title']    = $lang->gitlab->name;
$config->gitlab->dtable->fieldList['name']['name']     = 'name';
$config->gitlab->dtable->fieldList['name']['type']     = 'desc';
$config->gitlab->dtable->fieldList['name']['sortType'] = true;
$config->gitlab->dtable->fieldList['name']['hint']     = true;
$config->gitlab->dtable->fieldList['name']['minWidth'] = '356';

$config->gitlab->dtable->fieldList['url']['title']    = $lang->gitlab->url;
$config->gitlab->dtable->fieldList['url']['name']     = 'url';
$config->gitlab->dtable->fieldList['url']['type']     = 'desc';
$config->gitlab->dtable->fieldList['url']['sortType'] = true;
$config->gitlab->dtable->fieldList['url']['hint']     = true;
$config->gitlab->dtable->fieldList['url']['minWidth'] = '356';

$config->gitlab->actionList = array();
$config->gitlab->actionList['edit']['icon'] = 'edit';
$config->gitlab->actionList['edit']['text'] = $lang->gitlab->edit;
$config->gitlab->actionList['edit']['hint'] = $lang->gitlab->edit;
$config->gitlab->actionList['edit']['url']  = helper::createLink('gitlab', 'edit',"gitlabID={id}");

$config->gitlab->actionList['bindUser']['icon'] = 'lock';
$config->gitlab->actionList['bindUser']['text'] = $lang->gitlab->bindUser;
$config->gitlab->actionList['bindUser']['hint'] = $lang->gitlab->bindUser;
$config->gitlab->actionList['bindUser']['url']  = helper::createLink('gitlab', 'bindUser',"gitlabID={id}");

$config->gitlab->actionList['delete']['icon']       = 'trash';
$config->gitlab->actionList['delete']['text']       = $lang->gitlab->delete;
$config->gitlab->actionList['delete']['hint']       = $lang->gitlab->delete;
$config->gitlab->actionList['delete']['ajaxSubmit'] = true;
$config->gitlab->actionList['delete']['url']        = helper::createLink('gitlab', 'delete',"gitlabID={id}");

$config->gitlab->dtable->fieldList['actions']['name']     = 'actions';
$config->gitlab->dtable->fieldList['actions']['title']    = $lang->actions;
$config->gitlab->dtable->fieldList['actions']['type']     = 'actions';
$config->gitlab->dtable->fieldList['actions']['width']    = '160';
$config->gitlab->dtable->fieldList['actions']['sortType'] = false;
$config->gitlab->dtable->fieldList['actions']['fixed']    = 'right';
$config->gitlab->dtable->fieldList['actions']['menu']     = array('edit', 'bindUser', 'delete');
$config->gitlab->dtable->fieldList['actions']['list']     = $config->gitlab->actionList;
