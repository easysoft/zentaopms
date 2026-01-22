<?php
global $lang, $app;
$app->loadLang('job');
$app->loadLang('compile');

$config->pipeline->dtable = new stdclass();

$config->pipeline->dtable->fieldList['id']['title']    = 'ID';
$config->pipeline->dtable->fieldList['id']['name']     = 'id';
$config->pipeline->dtable->fieldList['id']['fixed']    = 'left';
$config->pipeline->dtable->fieldList['id']['type']     = 'id';
$config->pipeline->dtable->fieldList['id']['sortType'] = 'text';
$config->pipeline->dtable->fieldList['id']['checkbox'] = false;

$config->pipeline->dtable->fieldList['name']['title']       = $lang->pipeline->name;
$config->pipeline->dtable->fieldList['name']['name']        = 'name';
$config->pipeline->dtable->fieldList['name']['fixed']       = 'left';
$config->pipeline->dtable->fieldList['name']['type']        = 'desc';
$config->pipeline->dtable->fieldList['name']['sortType']    = true;
$config->pipeline->dtable->fieldList['name']['minWidth']    = '350';
$config->pipeline->dtable->fieldList['name']['hint']        = true;
$config->pipeline->dtable->fieldList['name']['show']        = true;
$config->pipeline->dtable->fieldList['name']['required']    = true;
$config->pipeline->dtable->fieldList['name']['checkbox']    = false;
$config->pipeline->dtable->fieldList['name']['link']        = array('module' => 'job', 'method' => 'view', 'params' => 'jobID={id}');
$config->pipeline->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->pipeline->dtable->fieldList['lastStatus']['title']    = $lang->pipeline->lastStatus;
$config->pipeline->dtable->fieldList['lastStatus']['name']     = 'lastStatus';
$config->pipeline->dtable->fieldList['lastStatus']['sortType'] = true;
$config->pipeline->dtable->fieldList['lastStatus']['width']    = '110';
$config->pipeline->dtable->fieldList['lastStatus']['hint']     = true;
$config->pipeline->dtable->fieldList['lastStatus']['map']      = $lang->compile->statusList;
$config->pipeline->dtable->fieldList['lastStatus']['show']     = true;

$config->pipeline->dtable->fieldList['buildSpec']['title']    = $lang->pipeline->buildSpec;
$config->pipeline->dtable->fieldList['buildSpec']['name']     = 'buildSpec';
$config->pipeline->dtable->fieldList['buildSpec']['type']     = 'text';
$config->pipeline->dtable->fieldList['buildSpec']['sortType'] = false;
$config->pipeline->dtable->fieldList['buildSpec']['minWidth'] = '120';
$config->pipeline->dtable->fieldList['buildSpec']['hint']     = true;
$config->pipeline->dtable->fieldList['buildSpec']['show']     = true;

$config->pipeline->dtable->fieldList['space']['title']    = $lang->pipeline->space;
$config->pipeline->dtable->fieldList['space']['type']     = 'text';
$config->pipeline->dtable->fieldList['space']['sortType'] = false;
$config->pipeline->dtable->fieldList['space']['minWidth'] = '120';
$config->pipeline->dtable->fieldList['space']['hint']     = true;
$config->pipeline->dtable->fieldList['space']['show']     = true;

$config->pipeline->dtable->fieldList['productName']['title']    = $lang->pipeline->product;
$config->pipeline->dtable->fieldList['productName']['type']     = 'text';
$config->pipeline->dtable->fieldList['productName']['sortType'] = false;
$config->pipeline->dtable->fieldList['productName']['minWidth'] = '120';
$config->pipeline->dtable->fieldList['productName']['hint']     = true;
$config->pipeline->dtable->fieldList['productName']['show']     = true;

$config->pipeline->dtable->fieldList['repoName']['title']    = $lang->pipeline->repo;
$config->pipeline->dtable->fieldList['repoName']['sortType'] = true;
$config->pipeline->dtable->fieldList['repoName']['width']    = '100';
$config->pipeline->dtable->fieldList['repoName']['hint']     = true;
$config->pipeline->dtable->fieldList['repoName']['show']     = true;

$config->pipeline->dtable->fieldList['engine']['title']    = $lang->pipeline->engine;
$config->pipeline->dtable->fieldList['engine']['name']     = 'engine';
$config->pipeline->dtable->fieldList['engine']['sortType'] = true;
$config->pipeline->dtable->fieldList['engine']['width']    = '80';
$config->pipeline->dtable->fieldList['engine']['hint']     = true;
$config->pipeline->dtable->fieldList['engine']['show']     = true;

$config->pipeline->dtable->fieldList['frame']['title']    = $lang->pipeline->frame;
$config->pipeline->dtable->fieldList['frame']['name']     = 'frame';
$config->pipeline->dtable->fieldList['frame']['sortType'] = true;
$config->pipeline->dtable->fieldList['frame']['width']    = '100';
$config->pipeline->dtable->fieldList['frame']['hint']     = true;
$config->pipeline->dtable->fieldList['frame']['show']     = true;

$config->pipeline->dtable->fieldList['triggerType']['title']    = $lang->pipeline->triggerType;
$config->pipeline->dtable->fieldList['triggerType']['name']     = 'triggerType';
$config->pipeline->dtable->fieldList['triggerType']['sortType'] = false;
$config->pipeline->dtable->fieldList['triggerType']['width']    = '100';
$config->pipeline->dtable->fieldList['triggerType']['hint']     = true;
$config->pipeline->dtable->fieldList['triggerType']['show']     = true;

$config->pipeline->dtable->fieldList['lastExec']['title']      = $lang->pipeline->lastExec;
$config->pipeline->dtable->fieldList['lastExec']['name']       = 'lastExec';
$config->pipeline->dtable->fieldList['lastExec']['type']       = 'datetime';
$config->pipeline->dtable->fieldList['lastExec']['sortType']   = true;
$config->pipeline->dtable->fieldList['lastExec']['hint']       = true;
$config->pipeline->dtable->fieldList['lastExec']['show']       = true;
$config->pipeline->dtable->fieldList['lastExec']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->pipeline->actionList = array();
$config->pipeline->actionList['compile']['icon'] = 'file-log';
$config->pipeline->actionList['compile']['text'] = $lang->compile->browse;
$config->pipeline->actionList['compile']['hint'] = $lang->compile->browse;
$config->pipeline->actionList['compile']['url']  = array('module' => 'compile', 'method' => 'browse', 'params' => "repoID={repo}&jobID={id}");

$config->pipeline->actionList['trigger']['icon'] = 'trigger';
$config->pipeline->actionList['trigger']['text'] = $lang->pipeline->trigger;
$config->pipeline->actionList['trigger']['hint'] = $lang->pipeline->trigger;
$config->pipeline->actionList['trigger']['url']  = helper::createLink('job', 'trigger',"jobID={id}");

$config->pipeline->actionList['edit']['icon'] = 'edit';
$config->pipeline->actionList['edit']['text'] = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['hint'] = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['url']  = helper::createLink('job', 'edit',"jobID={id}");

$config->pipeline->actionList['exec']['icon']      = 'play';
$config->pipeline->actionList['exec']['text']      = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['hint']      = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['className'] = 'ajax-submit';
$config->pipeline->actionList['exec']['url']       = helper::createLink('job', 'exec',"jobID={id}");

$config->pipeline->actionList['delete']['icon']       = 'trash';
$config->pipeline->actionList['delete']['text']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['hint']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['ajaxSubmit'] = true;
$config->pipeline->actionList['delete']['url']        = helper::createLink('job', 'delete',"jobID={id}");

$config->pipeline->dtable->fieldList['actions']['name']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['title'] = $lang->actions;
$config->pipeline->dtable->fieldList['actions']['width'] = 150;
$config->pipeline->dtable->fieldList['actions']['type']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['menu']  = array('compile', 'trigger', 'edit', 'exec', 'delete');
$config->pipeline->dtable->fieldList['actions']['list']  = $config->pipeline->actionList;
