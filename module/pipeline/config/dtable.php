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
$config->pipeline->dtable->fieldList['name']['minWidth']    = '260';
$config->pipeline->dtable->fieldList['name']['hint']        = true;
$config->pipeline->dtable->fieldList['name']['show']        = true;
$config->pipeline->dtable->fieldList['name']['required']    = true;
$config->pipeline->dtable->fieldList['name']['checkbox']    = false;
$config->pipeline->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->pipeline->dtable->fieldList['lastExecStatus']['title']    = $lang->pipeline->lastExecResult;
$config->pipeline->dtable->fieldList['lastExecStatus']['name']     = 'lastExecStatus';
$config->pipeline->dtable->fieldList['lastExecStatus']['sortType'] = false;
$config->pipeline->dtable->fieldList['lastExecStatus']['width']    = '110';
$config->pipeline->dtable->fieldList['lastExecStatus']['hint']     = true;
$config->pipeline->dtable->fieldList['lastExecStatus']['map']      = $lang->pipeline->execStatusList;
$config->pipeline->dtable->fieldList['lastExecStatus']['show']     = true;

$config->pipeline->dtable->fieldList['lastExecDate']['title']      = $lang->pipeline->lastExec;
$config->pipeline->dtable->fieldList['lastExecDate']['name']       = 'lastExecDate';
$config->pipeline->dtable->fieldList['lastExecDate']['type']       = 'datetime';
$config->pipeline->dtable->fieldList['lastExecDate']['sortType']   = false;
$config->pipeline->dtable->fieldList['lastExecDate']['width']      = '150';
$config->pipeline->dtable->fieldList['lastExecDate']['hint']       = true;
$config->pipeline->dtable->fieldList['lastExecDate']['show']       = true;
$config->pipeline->dtable->fieldList['lastExecDate']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->pipeline->dtable->fieldList['server']['title']    = $lang->pipeline->server;
$config->pipeline->dtable->fieldList['server']['name']     = 'providerName';
$config->pipeline->dtable->fieldList['server']['sortType'] = false;
$config->pipeline->dtable->fieldList['server']['width']    = '120';
$config->pipeline->dtable->fieldList['server']['hint']     = true;
$config->pipeline->dtable->fieldList['server']['display']  = false;

$config->pipeline->dtable->fieldList['status']['title']    = $lang->pipeline->status;
$config->pipeline->dtable->fieldList['status']['name']     = 'status';
$config->pipeline->dtable->fieldList['status']['sortType'] = true;
$config->pipeline->dtable->fieldList['status']['width']    = '80';
$config->pipeline->dtable->fieldList['status']['hint']     = true;
$config->pipeline->dtable->fieldList['status']['map']      = $lang->pipeline->statusList;
$config->pipeline->dtable->fieldList['status']['show']     = true;

$config->pipeline->dtable->fieldList['desc']['title']  = $lang->pipeline->desc;
$config->pipeline->dtable->fieldList['desc']['name']   = 'desc';
$config->pipeline->dtable->fieldList['desc']['type']   = 'html';
$config->pipeline->dtable->fieldList['desc']['hint']   = true;
$config->pipeline->dtable->fieldList['desc']['show']   = true;

$config->pipeline->dtable->fieldList['repo']['title']    = $lang->pipeline->repo;
$config->pipeline->dtable->fieldList['repo']['name']     = 'repoName';
$config->pipeline->dtable->fieldList['repo']['sortType'] = false;
$config->pipeline->dtable->fieldList['repo']['width']    = '120';
$config->pipeline->dtable->fieldList['repo']['hint']     = true;
$config->pipeline->dtable->fieldList['repo']['show']     = true;

$config->pipeline->actionList = array();
$config->pipeline->actionList['exec']['icon']        = 'play';
$config->pipeline->actionList['exec']['text']        = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['hint']        = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['url']         = array('module' => 'pipeline', 'method' => 'exec', 'params' => "pipelineID={id}");
$config->pipeline->actionList['exec']['data-toggle'] = 'modal';

$config->pipeline->actionList['execution']['icon'] = 'file-log';
$config->pipeline->actionList['execution']['text'] = $lang->pipeline->execution;
$config->pipeline->actionList['execution']['hint'] = $lang->pipeline->execution;
$config->pipeline->actionList['execution']['url']  = array('module' => 'pipeline', 'method' => 'execution', 'params' => "spaceID={spaceID}&repoID={repoID}&type={scope}&pipelineID={id}");

$config->pipeline->actionList['edit']['icon']        = 'edit';
$config->pipeline->actionList['edit']['text']        = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['hint']        = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['url']         = helper::createLink('pipeline', 'edit', "id={id}");

$config->pipeline->actionList['delete']['icon']       = 'trash';
$config->pipeline->actionList['delete']['text']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['hint']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['ajaxSubmit'] = true;
$config->pipeline->actionList['delete']['url']        = helper::createLink('pipeline', 'delete', "id={id}");

$config->pipeline->dtable->fieldList['actions']['name']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['title'] = $lang->actions;
$config->pipeline->dtable->fieldList['actions']['width'] = 170;
$config->pipeline->dtable->fieldList['actions']['type']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['menu']  = array('exec', 'execution', 'delete');
$config->pipeline->dtable->fieldList['actions']['list']  = $config->pipeline->actionList;

$config->pipeline->execution->dtable = new stdclass();

$config->pipeline->execution->dtable->fieldList['id']['title']    = 'ID';
$config->pipeline->execution->dtable->fieldList['id']['name']     = 'id';
$config->pipeline->execution->dtable->fieldList['id']['fixed']    = 'left';
$config->pipeline->execution->dtable->fieldList['id']['type']     = 'id';
$config->pipeline->execution->dtable->fieldList['id']['sortType'] = false;

$config->pipeline->execution->dtable->fieldList['scope']['title']    = $lang->pipeline->level;
$config->pipeline->execution->dtable->fieldList['scope']['name']     = 'scope';
$config->pipeline->execution->dtable->fieldList['scope']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['scope']['width']    = '100';
$config->pipeline->execution->dtable->fieldList['scope']['hint']     = true;
$config->pipeline->execution->dtable->fieldList['scope']['map']      = $lang->pipeline->typeList;

$config->pipeline->execution->dtable->fieldList['ref']['title']    = $lang->pipeline->branch;
$config->pipeline->execution->dtable->fieldList['ref']['name']     = 'ref';
$config->pipeline->execution->dtable->fieldList['ref']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['ref']['hint']     = true;
$config->pipeline->execution->dtable->fieldList['ref']['width']    = '150';

$config->pipeline->execution->dtable->fieldList['name']['title']    = $lang->pipeline->pipelineName;
$config->pipeline->execution->dtable->fieldList['name']['name']     = 'pipelineName';
$config->pipeline->execution->dtable->fieldList['name']['fixed']    = 'left';
$config->pipeline->execution->dtable->fieldList['name']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['name']['minWidth'] = '350';
$config->pipeline->execution->dtable->fieldList['name']['hint']     = true;

$config->pipeline->execution->dtable->fieldList['status']['title']    = $lang->pipeline->status;
$config->pipeline->execution->dtable->fieldList['status']['name']     = 'status';
$config->pipeline->execution->dtable->fieldList['status']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['status']['width']    = '110';
$config->pipeline->execution->dtable->fieldList['status']['hint']     = true;
$config->pipeline->execution->dtable->fieldList['status']['map']      = $lang->pipeline->execStatusList;

$config->pipeline->execution->dtable->fieldList['createdBy']['title']    = $lang->pipeline->triggerPerson;
$config->pipeline->execution->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->pipeline->execution->dtable->fieldList['createdBy']['type']     = 'user';
$config->pipeline->execution->dtable->fieldList['createdBy']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['createdBy']['hint']     = true;

$config->pipeline->execution->dtable->fieldList['trigger']['title']    = $lang->pipeline->triggerType;
$config->pipeline->execution->dtable->fieldList['trigger']['name']     = 'trigger';
$config->pipeline->execution->dtable->fieldList['trigger']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['trigger']['width']    = '100';
$config->pipeline->execution->dtable->fieldList['trigger']['hint']     = true;
$config->pipeline->execution->dtable->fieldList['trigger']['map']      = $lang->pipeline->triggerTypeList;

$config->pipeline->execution->dtable->fieldList['repo']['title']    = $lang->pipeline->repo;
$config->pipeline->execution->dtable->fieldList['repo']['name']     = 'repo';
$config->pipeline->execution->dtable->fieldList['repo']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['repo']['width']    = '100';
$config->pipeline->execution->dtable->fieldList['repo']['hint']     = true;

$config->pipeline->execution->dtable->fieldList['duration']['title']    = $lang->pipeline->duration;
$config->pipeline->execution->dtable->fieldList['duration']['name']     = 'duration';
$config->pipeline->execution->dtable->fieldList['duration']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['duration']['hint']     = true;

$config->pipeline->execution->dtable->fieldList['createdDate']['title']    = $lang->pipeline->triggerDate;
$config->pipeline->execution->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->pipeline->execution->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->pipeline->execution->dtable->fieldList['createdDate']['sortType'] = false;
$config->pipeline->execution->dtable->fieldList['createdDate']['width']    = '110';
$config->pipeline->execution->dtable->fieldList['createdDate']['hint']     = true;

$config->pipeline->execution->actionList = array();
$config->pipeline->execution->actionList['view']['icon'] = 'info';
$config->pipeline->execution->actionList['view']['text'] = $lang->pipeline->log;
$config->pipeline->execution->actionList['view']['hint'] = $lang->pipeline->log;
$config->pipeline->execution->actionList['view']['url']  = array('module' => 'pipeline', 'method' => 'execView', 'params' => "execID={id}");

$config->pipeline->execution->dtable->fieldList['actions']['name']  = 'actions';
$config->pipeline->execution->dtable->fieldList['actions']['title'] = $lang->actions;
$config->pipeline->execution->dtable->fieldList['actions']['width'] = 100;
$config->pipeline->execution->dtable->fieldList['actions']['type']  = 'actions';
$config->pipeline->execution->dtable->fieldList['actions']['menu']  = array('view');
$config->pipeline->execution->dtable->fieldList['actions']['list']  = $config->pipeline->execution->actionList;
