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
$config->job->dtable->fieldList['id']['width']    = '80';

$config->job->dtable->fieldList['name']['title']    = $lang->job->name;
$config->job->dtable->fieldList['name']['name']     = 'name';
$config->job->dtable->fieldList['name']['fixed']    = 'left';
$config->job->dtable->fieldList['name']['type']     = 'title';
$config->job->dtable->fieldList['name']['sortType'] = true;
$config->job->dtable->fieldList['name']['hint']     = true;
$config->job->dtable->fieldList['name']['minWidth'] = '356';

$config->job->dtable->fieldList['repo']['title']    = $lang->job->repo;
$config->job->dtable->fieldList['repo']['name']     = 'repoName';
$config->job->dtable->fieldList['repo']['type']     = 'title';
$config->job->dtable->fieldList['repo']['sortType'] = true;
$config->job->dtable->fieldList['repo']['width']    = '100';

$config->job->dtable->fieldList['engine']['title']    = $lang->job->engine;
$config->job->dtable->fieldList['engine']['name']     = 'engine';
$config->job->dtable->fieldList['engine']['type']     = 'text';
$config->job->dtable->fieldList['engine']['sortType'] = true;
$config->job->dtable->fieldList['engine']['width']    = '80';

$config->job->dtable->fieldList['frame']['title']    = $lang->job->frame;
$config->job->dtable->fieldList['frame']['name']     = 'frame';
$config->job->dtable->fieldList['frame']['type']     = 'text';
$config->job->dtable->fieldList['frame']['sortType'] = true;
$config->job->dtable->fieldList['frame']['show']     = true;
$config->job->dtable->fieldList['frame']['width']    = '120';

$config->job->dtable->fieldList['buildSpec']['title']    = $lang->job->buildSpec;
$config->job->dtable->fieldList['buildSpec']['name']     = 'buildSpec';
$config->job->dtable->fieldList['buildSpec']['type']     = 'text';
$config->job->dtable->fieldList['buildSpec']['sortType'] = false;
$config->job->dtable->fieldList['buildSpec']['width']    = '160';

$config->job->dtable->fieldList['triggerType']['title']    = $lang->job->triggerType;
$config->job->dtable->fieldList['triggerType']['name']     = 'triggerType';
$config->job->dtable->fieldList['triggerType']['type']     = 'text';
$config->job->dtable->fieldList['triggerType']['sortType'] = false;
$config->job->dtable->fieldList['triggerType']['width']    = '120';

$config->job->dtable->fieldList['lastStatus']['title']    = $lang->job->lastStatus;
$config->job->dtable->fieldList['lastStatus']['name']     = 'lastStatus';
$config->job->dtable->fieldList['lastStatus']['type']     = 'status';
$config->job->dtable->fieldList['lastStatus']['sortType'] = true;
$config->job->dtable->fieldList['lastStatus']['width']    = '120';

$config->job->dtable->fieldList['lastExec']['title']    = $lang->job->lastExec;
$config->job->dtable->fieldList['lastExec']['name']     = 'lastExec';
$config->job->dtable->fieldList['lastExec']['type']     = 'date';
$config->job->dtable->fieldList['lastExec']['sortType'] = true;
$config->job->dtable->fieldList['lastExec']['width']    = '120';

$config->job->actionList = array();
$config->job->actionList['compile']['icon'] = 'history';
$config->job->actionList['compile']['text'] = $lang->compile->browse;
$config->job->actionList['compile']['hint'] = $lang->compile->browse;
$config->job->actionList['compile']['url']  = helper::createLink('compile', 'browse',"repoID={repo}&jobID={id}");

$config->job->actionList['edit']['icon'] = 'edit';
$config->job->actionList['edit']['text'] = $lang->job->edit;
$config->job->actionList['edit']['hint'] = $lang->job->edit;
$config->job->actionList['edit']['url']  = helper::createLink('job', 'edit',"jobID={id}");

$config->job->actionList['exec']['icon'] = 'play';
$config->job->actionList['exec']['text'] = $lang->job->exec;
$config->job->actionList['exec']['hint'] = $lang->job->exec;
$config->job->actionList['exec']['url']  = helper::createLink('job', 'exec',"jobID={id}");

$config->job->actionList['delete']['icon']       = 'trash';
$config->job->actionList['delete']['text']       = $lang->job->delete;
$config->job->actionList['delete']['hint']       = $lang->job->delete;
$config->job->actionList['delete']['ajaxSubmit'] = true;
$config->job->actionList['delete']['url']        = helper::createLink('job', 'delete',"jobID={id}");

$config->job->dtable->fieldList['actions']['name']     = 'actions';
$config->job->dtable->fieldList['actions']['title']    = $lang->actions;
$config->job->dtable->fieldList['actions']['type']     = 'actions';
$config->job->dtable->fieldList['actions']['width']    = '160';
$config->job->dtable->fieldList['actions']['sortType'] = false;
$config->job->dtable->fieldList['actions']['fixed']    = 'right';
$config->job->dtable->fieldList['actions']['menu']     = array('compile', 'edit', 'exec', 'delete');
$config->job->dtable->fieldList['actions']['list']     = $config->job->actionList;
