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
$config->pipeline->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->pipeline->dtable->fieldList['status']['title']    = $lang->pipeline->status;
$config->pipeline->dtable->fieldList['status']['name']     = 'status';
$config->pipeline->dtable->fieldList['status']['sortType'] = true;
$config->pipeline->dtable->fieldList['status']['width']    = '110';
$config->pipeline->dtable->fieldList['status']['hint']     = true;
$config->pipeline->dtable->fieldList['status']['map']      = $lang->pipeline->statusList;
$config->pipeline->dtable->fieldList['status']['show']     = true;

$config->pipeline->dtable->fieldList['lastExecStatus']['title']    = $lang->pipeline->lastStatus;
$config->pipeline->dtable->fieldList['lastExecStatus']['name']     = 'lastExecStatus';
$config->pipeline->dtable->fieldList['lastExecStatus']['sortType'] = false;
$config->pipeline->dtable->fieldList['lastExecStatus']['width']    = '110';
$config->pipeline->dtable->fieldList['lastExecStatus']['hint']     = true;
$config->pipeline->dtable->fieldList['lastExecStatus']['map']      = $lang->pipeline->execStatusList;
$config->pipeline->dtable->fieldList['lastExecStatus']['show']     = true;

$config->pipeline->dtable->fieldList['triggerPerson']['title']    = $lang->pipeline->triggerPerson;
$config->pipeline->dtable->fieldList['triggerPerson']['name']     = 'triggerPerson';
$config->pipeline->dtable->fieldList['triggerPerson']['type']     = 'user';
$config->pipeline->dtable->fieldList['triggerPerson']['sortType'] = false;
$config->pipeline->dtable->fieldList['triggerPerson']['minWidth'] = '120';
$config->pipeline->dtable->fieldList['triggerPerson']['hint']     = true;
$config->pipeline->dtable->fieldList['triggerPerson']['show']     = true;

$config->pipeline->dtable->fieldList['triggerType']['title']    = $lang->pipeline->triggerType;
$config->pipeline->dtable->fieldList['triggerType']['name']     = 'triggerType';
$config->pipeline->dtable->fieldList['triggerType']['sortType'] = false;
$config->pipeline->dtable->fieldList['triggerType']['width']    = '100';
$config->pipeline->dtable->fieldList['triggerType']['hint']     = true;
$config->pipeline->dtable->fieldList['triggerType']['show']     = true;
$config->pipeline->dtable->fieldList['triggerType']['map']      = $lang->pipeline->triggerTypeList;

$config->pipeline->dtable->fieldList['repo']['title']    = $lang->pipeline->repo;
$config->pipeline->dtable->fieldList['repo']['name']     = 'repoName';
$config->pipeline->dtable->fieldList['repo']['sortType'] = true;
$config->pipeline->dtable->fieldList['repo']['width']    = '100';
$config->pipeline->dtable->fieldList['repo']['hint']     = true;
$config->pipeline->dtable->fieldList['repo']['show']     = true;

$config->pipeline->dtable->fieldList['lastExecDate']['title']      = $lang->pipeline->lastExec;
$config->pipeline->dtable->fieldList['lastExecDate']['name']       = 'lastExecDate';
$config->pipeline->dtable->fieldList['lastExecDate']['type']       = 'datetime';
$config->pipeline->dtable->fieldList['lastExecDate']['sortType']   = false;
$config->pipeline->dtable->fieldList['lastExecDate']['hint']       = true;
$config->pipeline->dtable->fieldList['lastExecDate']['show']       = true;
$config->pipeline->dtable->fieldList['lastExecDate']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->pipeline->actionList = array();
$config->pipeline->actionList['compile']['icon'] = 'file-log';
$config->pipeline->actionList['compile']['text'] = $lang->compile->browse;
$config->pipeline->actionList['compile']['hint'] = $lang->compile->browse;
$config->pipeline->actionList['compile']['url']  = array('module' => 'pipeline', 'method' => 'execution', 'params' => "spaceID={spaceID}&repoID={repo}&jobID={id}");

$config->pipeline->actionList['edit']['icon'] = 'edit';
$config->pipeline->actionList['edit']['text'] = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['hint'] = $lang->pipeline->edit;
$config->pipeline->actionList['edit']['url']  = helper::createLink('pipeline', 'edit',"id={id}");

$config->pipeline->actionList['exec']['icon']      = 'play';
$config->pipeline->actionList['exec']['text']      = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['hint']      = $lang->pipeline->exec;
$config->pipeline->actionList['exec']['className'] = 'ajax-submit';
$config->pipeline->actionList['exec']['url']       = helper::createLink('pipeline', 'exec',"id={id}");

$config->pipeline->actionList['delete']['icon']       = 'trash';
$config->pipeline->actionList['delete']['text']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['hint']       = $lang->pipeline->delete;
$config->pipeline->actionList['delete']['ajaxSubmit'] = true;
$config->pipeline->actionList['delete']['url']        = helper::createLink('pipeline', 'delete',"id={id}");

$config->pipeline->dtable->fieldList['actions']['name']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['title'] = $lang->actions;
$config->pipeline->dtable->fieldList['actions']['width'] = 150;
$config->pipeline->dtable->fieldList['actions']['type']  = 'actions';
$config->pipeline->dtable->fieldList['actions']['menu']  = array('compile', 'edit', 'exec', 'delete');
$config->pipeline->dtable->fieldList['actions']['list']  = $config->pipeline->actionList;
