<?php
global $lang, $app;
$app->loadLang('sonarqube');

$config->sonarqube->dtable = new stdclass();

$config->sonarqube->dtable->fieldList['id']['title']    = 'ID';
$config->sonarqube->dtable->fieldList['id']['name']     = 'id';
$config->sonarqube->dtable->fieldList['id']['type']     = 'number';
$config->sonarqube->dtable->fieldList['id']['sortType'] = 'desc';
$config->sonarqube->dtable->fieldList['id']['checkbox'] = false;
$config->sonarqube->dtable->fieldList['id']['width']    = '80';

$config->sonarqube->dtable->fieldList['name']['title']    = $lang->sonarqube->name;
$config->sonarqube->dtable->fieldList['name']['name']     = 'name';
$config->sonarqube->dtable->fieldList['name']['type']     = 'desc';
$config->sonarqube->dtable->fieldList['name']['sortType'] = true;
$config->sonarqube->dtable->fieldList['name']['hint']     = true;
$config->sonarqube->dtable->fieldList['name']['minWidth'] = '356';

$config->sonarqube->dtable->fieldList['url']['title']    = $lang->sonarqube->url;
$config->sonarqube->dtable->fieldList['url']['name']     = 'url';
$config->sonarqube->dtable->fieldList['url']['type']     = 'desc';
$config->sonarqube->dtable->fieldList['url']['sortType'] = true;
$config->sonarqube->dtable->fieldList['url']['hint']     = true;
$config->sonarqube->dtable->fieldList['url']['minWidth'] = '356';

$config->sonarqube->actionList = array();
$config->sonarqube->actionList['list']['icon'] = 'list';
$config->sonarqube->actionList['list']['text'] = $lang->sonarqube->browseProject;
$config->sonarqube->actionList['list']['hint'] = $lang->sonarqube->browseProject;
$config->sonarqube->actionList['list']['url']  = helper::createLink('sonarqube', 'browseProject',"sonarqubeID={id}");

$config->sonarqube->actionList['edit']['icon'] = 'edit';
$config->sonarqube->actionList['edit']['text'] = $lang->sonarqube->edit;
$config->sonarqube->actionList['edit']['hint'] = $lang->sonarqube->edit;
$config->sonarqube->actionList['edit']['url']  = helper::createLink('sonarqube', 'edit',"sonarqubeID={id}");

$config->sonarqube->actionList['delete']['icon']       = 'trash';
$config->sonarqube->actionList['delete']['text']       = $lang->sonarqube->delete;
$config->sonarqube->actionList['delete']['hint']       = $lang->sonarqube->delete;
$config->sonarqube->actionList['delete']['ajaxSubmit'] = true;
$config->sonarqube->actionList['delete']['url']        = helper::createLink('sonarqube', 'delete',"sonarqubeID={id}");

$config->sonarqube->dtable->fieldList['actions']['name']     = 'actions';
$config->sonarqube->dtable->fieldList['actions']['title']    = $lang->actions;
$config->sonarqube->dtable->fieldList['actions']['type']     = 'actions';
$config->sonarqube->dtable->fieldList['actions']['width']    = '160';
$config->sonarqube->dtable->fieldList['actions']['sortType'] = false;
$config->sonarqube->dtable->fieldList['actions']['fixed']    = 'right';
$config->sonarqube->dtable->fieldList['actions']['menu']     = array('list', 'edit', 'delete');
$config->sonarqube->dtable->fieldList['actions']['list']     = $config->sonarqube->actionList;
