<?php
global $lang, $app;
$app->loadLang('job');
$app->loadLang('compile');

$config->job->dtable = new stdclass();

$config->job->dtable->fieldList['id']['title']    = 'ID';
$config->job->dtable->fieldList['id']['name']     = 'id';
$config->job->dtable->fieldList['id']['fixed']    = 'left';
$config->job->dtable->fieldList['id']['type']     = 'id';
$config->job->dtable->fieldList['id']['sortType'] = 'text';
$config->job->dtable->fieldList['id']['checkbox'] = false;

$config->job->dtable->fieldList['name']['title']       = $lang->job->name;
$config->job->dtable->fieldList['name']['name']        = 'name';
$config->job->dtable->fieldList['name']['fixed']       = 'left';
$config->job->dtable->fieldList['name']['type']        = 'desc';
$config->job->dtable->fieldList['name']['sortType']    = true;
$config->job->dtable->fieldList['name']['minWidth']    = '150';
$config->job->dtable->fieldList['name']['hint']        = true;
$config->job->dtable->fieldList['name']['checkbox']    = false;
$config->job->dtable->fieldList['name']['link']        = array('module' => 'job', 'method' => 'view', 'params' => 'jobID={id}');
$config->job->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->job->dtable->fieldList['lastStatus']['title']    = $lang->job->lastStatus;
$config->job->dtable->fieldList['lastStatus']['name']     = 'lastStatus';
$config->job->dtable->fieldList['lastStatus']['sortType'] = true;
$config->job->dtable->fieldList['lastStatus']['width']    = '110';
$config->job->dtable->fieldList['lastStatus']['hint']     = true;
$config->job->dtable->fieldList['lastStatus']['map']      = $lang->compile->statusList;

$config->job->dtable->fieldList['buildSpec']['title']    = $lang->job->buildSpec;
$config->job->dtable->fieldList['buildSpec']['name']     = 'buildSpec';
$config->job->dtable->fieldList['buildSpec']['type']     = 'text';
$config->job->dtable->fieldList['buildSpec']['sortType'] = false;
$config->job->dtable->fieldList['product']['minWidth']   = '120';
$config->job->dtable->fieldList['buildSpec']['hint']     = true;

$config->job->dtable->fieldList['product']['title']    = $lang->job->product;
$config->job->dtable->fieldList['product']['name']     = 'productName';
$config->job->dtable->fieldList['product']['type']     = 'text';
$config->job->dtable->fieldList['product']['sortType'] = false;
$config->job->dtable->fieldList['product']['minWidth'] = '120';
$config->job->dtable->fieldList['product']['hint']     = true;

$config->job->dtable->fieldList['repo']['title']    = $lang->job->repo;
$config->job->dtable->fieldList['repo']['name']     = 'repoName';
$config->job->dtable->fieldList['repo']['sortType'] = true;
$config->job->dtable->fieldList['repo']['width']    = '100';
$config->job->dtable->fieldList['repo']['hint']     = true;

$config->job->dtable->fieldList['engine']['title']    = $lang->job->engine;
$config->job->dtable->fieldList['engine']['name']     = 'engine';
$config->job->dtable->fieldList['engine']['sortType'] = true;
$config->job->dtable->fieldList['engine']['width']    = '80';
$config->job->dtable->fieldList['engine']['hint']     = true;

$config->job->dtable->fieldList['frame']['title']    = $lang->job->frame;
$config->job->dtable->fieldList['frame']['name']     = 'frame';
$config->job->dtable->fieldList['frame']['sortType'] = true;
$config->job->dtable->fieldList['frame']['width']    = '100';
$config->job->dtable->fieldList['frame']['hint']     = true;

$config->job->dtable->fieldList['triggerType']['title']    = $lang->job->triggerType;
$config->job->dtable->fieldList['triggerType']['name']     = 'triggerType';
$config->job->dtable->fieldList['triggerType']['sortType'] = false;
$config->job->dtable->fieldList['triggerType']['width']    = '100';
$config->job->dtable->fieldList['triggerType']['hint']     = true;

$config->job->dtable->fieldList['lastExec']['title']    = $lang->job->lastExec;
$config->job->dtable->fieldList['lastExec']['name']     = 'lastExec';
$config->job->dtable->fieldList['lastExec']['type']     = 'datetime';
$config->job->dtable->fieldList['lastExec']['sortType'] = true;
$config->job->dtable->fieldList['lastExec']['hint']     = true;

$config->job->actionList = array();
$config->job->actionList['compile']['icon'] = 'history';
$config->job->actionList['compile']['text'] = $lang->compile->browse;
$config->job->actionList['compile']['hint'] = $lang->compile->browse;
$config->job->actionList['compile']['url']  = array('module' => 'compile', 'method' => 'browse', 'params' => "repoID={repo}&jobID={id}");

$config->job->actionList['edit']['icon'] = 'edit';
$config->job->actionList['edit']['text'] = $lang->job->edit;
$config->job->actionList['edit']['hint'] = $lang->job->edit;
$config->job->actionList['edit']['url']  = helper::createLink('job', 'edit',"jobID={id}");

$config->job->actionList['exec']['icon']      = 'play';
$config->job->actionList['exec']['text']      = $lang->job->exec;
$config->job->actionList['exec']['hint']      = $lang->job->exec;
$config->job->actionList['exec']['className'] = 'ajax-submit';
$config->job->actionList['exec']['url']       = helper::createLink('job', 'exec',"jobID={id}");

$config->job->actionList['delete']['icon']       = 'trash';
$config->job->actionList['delete']['text']       = $lang->job->delete;
$config->job->actionList['delete']['hint']       = $lang->job->delete;
$config->job->actionList['delete']['ajaxSubmit'] = true;
$config->job->actionList['delete']['url']        = helper::createLink('job', 'delete',"jobID={id}");

$config->job->dtable->fieldList['actions']['name']     = 'actions';
$config->job->dtable->fieldList['actions']['title']    = $lang->actions;
$config->job->dtable->fieldList['actions']['type']     = 'actions';
$config->job->dtable->fieldList['actions']['width']    = '130';
$config->job->dtable->fieldList['actions']['sortType'] = false;
$config->job->dtable->fieldList['actions']['fixed']    = 'right';
$config->job->dtable->fieldList['actions']['menu']     = array('compile', 'edit', 'exec', 'delete');
$config->job->dtable->fieldList['actions']['list']     = $config->job->actionList;
