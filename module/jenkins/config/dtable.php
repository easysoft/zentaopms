<?php
global $lang, $app;
$app->loadLang('jenkins');

$config->jenkins->dtable = new stdclass();

$config->jenkins->dtable->fieldList['id']['title']    = 'ID';
$config->jenkins->dtable->fieldList['id']['name']     = 'id';
$config->jenkins->dtable->fieldList['id']['type']     = 'number';
$config->jenkins->dtable->fieldList['id']['sortType'] = 'desc';
$config->jenkins->dtable->fieldList['id']['checkbox'] = false;
$config->jenkins->dtable->fieldList['id']['width']    = '80';

$config->jenkins->dtable->fieldList['name']['title']    = $lang->jenkins->name;
$config->jenkins->dtable->fieldList['name']['name']     = 'name';
$config->jenkins->dtable->fieldList['name']['type']     = 'desc';
$config->jenkins->dtable->fieldList['name']['sortType'] = true;
$config->jenkins->dtable->fieldList['name']['hint']     = true;
$config->jenkins->dtable->fieldList['name']['minWidth'] = '356';

$config->jenkins->dtable->fieldList['url']['title']    = $lang->jenkins->url;
$config->jenkins->dtable->fieldList['url']['name']     = 'url';
$config->jenkins->dtable->fieldList['url']['type']     = 'desc';
$config->jenkins->dtable->fieldList['url']['sortType'] = true;
$config->jenkins->dtable->fieldList['url']['hint']     = true;
$config->jenkins->dtable->fieldList['url']['minWidth'] = '356';

$config->jenkins->actionList = array();
$config->jenkins->actionList['edit']['icon'] = 'edit';
$config->jenkins->actionList['edit']['text'] = $lang->jenkins->edit;
$config->jenkins->actionList['edit']['hint'] = $lang->jenkins->edit;
$config->jenkins->actionList['edit']['url']  = helper::createLink('jenkins', 'edit',"jenkinsID={id}");

$config->jenkins->actionList['delete']['icon']       = 'trash';
$config->jenkins->actionList['delete']['text']       = $lang->jenkins->delete;
$config->jenkins->actionList['delete']['hint']       = $lang->jenkins->delete;
$config->jenkins->actionList['delete']['ajaxSubmit'] = true;
$config->jenkins->actionList['delete']['url']        = helper::createLink('jenkins', 'delete',"jenkinsID={id}");

$config->jenkins->dtable->fieldList['actions']['name']     = 'actions';
$config->jenkins->dtable->fieldList['actions']['title']    = $lang->actions;
$config->jenkins->dtable->fieldList['actions']['type']     = 'actions';
$config->jenkins->dtable->fieldList['actions']['width']    = '160';
$config->jenkins->dtable->fieldList['actions']['sortType'] = false;
$config->jenkins->dtable->fieldList['actions']['fixed']    = 'right';
$config->jenkins->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
$config->jenkins->dtable->fieldList['actions']['list']     = $config->jenkins->actionList;
