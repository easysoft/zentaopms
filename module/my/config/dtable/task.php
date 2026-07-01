<?php
$config->my->task = new stdclass();
$config->my->task->actionList = array();
$config->my->task->actionList['confirmStoryChange']['icon']      = 'search';
$config->my->task->actionList['confirmStoryChange']['text']      = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['hint']      = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['url']       = array('module' => 'task', 'method' => 'confirmStoryChange', 'params' => 'taskID={id}');
$config->my->task->actionList['confirmStoryChange']['className'] = 'ajax-submit';

$config->my->task->actionList['start']['icon']        = 'play';
$config->my->task->actionList['start']['text']        = $lang->task->start;
$config->my->task->actionList['start']['hint']        = $lang->task->start;
$config->my->task->actionList['start']['url']         = array('module' => 'task', 'method' => 'start', 'params' => 'taskID={id}');
$config->my->task->actionList['start']['data-toggle'] = 'modal';

$config->my->task->actionList['restart']['icon']        = 'play';
$config->my->task->actionList['restart']['text']        = $lang->task->restart;
$config->my->task->actionList['restart']['hint']        = $lang->task->restart;
$config->my->task->actionList['restart']['url']         = array('module' => 'task', 'method' => 'restart', 'params' => 'taskID={id}');
$config->my->task->actionList['restart']['data-toggle'] = 'modal';

$config->my->task->actionList['finish']['icon']        = 'checked';
$config->my->task->actionList['finish']['text']        = $lang->task->finish;
$config->my->task->actionList['finish']['hint']        = $lang->task->finish;
$config->my->task->actionList['finish']['url']         = array('module' => 'task', 'method' => 'finish', 'params' => 'taskID={id}');
$config->my->task->actionList['finish']['data-toggle'] = 'modal';

$config->my->task->actionList['close']['icon']        = 'off';
$config->my->task->actionList['close']['text']        = $lang->task->close;
$config->my->task->actionList['close']['hint']        = $lang->task->close;
$config->my->task->actionList['close']['url']         = array('module' => 'task', 'method' => 'close', 'params' => 'taskID={id}');
$config->my->task->actionList['close']['data-toggle'] = 'modal';

$config->my->task->actionList['record']['icon']          = 'time';
$config->my->task->actionList['record']['text']          = $lang->task->logEfforts;
$config->my->task->actionList['record']['hint']          = $lang->task->logEfforts;
$config->my->task->actionList['record']['url']           = array('module' => 'task', 'method' => 'recordWorkhour', 'params' => 'taskID={id}');
$config->my->task->actionList['record']['data-toggle']   = 'modal';
$config->my->task->actionList['record']['data-position'] = 'center';

$config->my->task->actionList['edit']['icon']          = 'edit';
$config->my->task->actionList['edit']['text']          = $lang->task->edit;
$config->my->task->actionList['edit']['hint']          = $lang->task->edit;
$config->my->task->actionList['edit']['url']           = array('module' => 'task', 'method' => 'edit', 'params' => 'taskID={id}');
$config->my->task->actionList['edit']['data-toggle']   = 'modal';
$config->my->task->actionList['edit']['data-size']     = 'lg';
$config->my->task->actionList['edit']['data-position'] = 'center';

if($config->vision != 'lite')
{
    $config->my->task->actionList['batchCreate']['icon']          = 'split';
    $config->my->task->actionList['batchCreate']['text']          = $lang->task->batchCreate;
    $config->my->task->actionList['batchCreate']['hint']          = $lang->task->children;
    $config->my->task->actionList['batchCreate']['url']           = array('module' => 'task', 'method' => 'batchCreate', 'params' => 'executionID={execution}&storyID={story}&moduleID={module}&taskID={id}&iframe=true');
    $config->my->task->actionList['batchCreate']['data-toggle']   = 'modal';
    $config->my->task->actionList['batchCreate']['data-size']     = 'lg';
    $config->my->task->actionList['batchCreate']['data-position'] = 'center';

    $space = ' ';
}

$config->my->task->dtable = new stdclass();
$config->my->task->dtable->fieldList['id']['name']     = 'id';
$config->my->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->task->dtable->fieldList['id']['type']     = 'checkID';
$config->my->task->dtable->fieldList['id']['sortType'] = true;
$config->my->task->dtable->fieldList['id']['show']     = true;

$config->my->task->dtable->fieldList['name']['name']         = 'name';
$config->my->task->dtable->fieldList['name']['title']        = $lang->task->name;
$config->my->task->dtable->fieldList['name']['type']         = 'title';
$config->my->task->dtable->fieldList['name']['nestedToggle'] = true;
$config->my->task->dtable->fieldList['name']['link']         = array('url' => array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}'), 'data-app' => 'execution');
$config->my->task->dtable->fieldList['name']['styleMap']     = array('--color-link' => 'color');
$config->my->task->dtable->fieldList['name']['fixed']        = 'left';
$config->my->task->dtable->fieldList['name']['data-toggle']  = 'modal';
$config->my->task->dtable->fieldList['name']['data-size']    = 'lg';
$config->my->task->dtable->fieldList['name']['sortType']     = true;

$config->my->task->dtable->fieldList['pri']['name']     = 'pri';
$config->my->task->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->task->dtable->fieldList['pri']['type']     = 'pri';
$config->my->task->dtable->fieldList['pri']['priList']  = $lang->task->priList;
$config->my->task->dtable->fieldList['pri']['group']    = 'pri';
$config->my->task->dtable->fieldList['pri']['sortType'] = true;
$config->my->task->dtable->fieldList['pri']['show']     = true;

$config->my->task->dtable->fieldList['status']['name']      = 'status';
$config->my->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->task->dtable->fieldList['status']['type']      = 'status';
$config->my->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList + array('changed' => $lang->task->storyChange);
$config->my->task->dtable->fieldList['status']['group']     = 'pri';
$config->my->task->dtable->fieldList['status']['sortType']  = true;
$config->my->task->dtable->fieldList['status']['show']     = true;

$config->my->task->dtable->fieldList['projectName']['name']     = 'projectName';
$config->my->task->dtable->fieldList['projectName']['title']    = $lang->task->project;
$config->my->task->dtable->fieldList['projectName']['type']     = 'text';
$config->my->task->dtable->fieldList['projectName']['link']     = array('module' => 'project', 'method' => 'view', 'params' => 'projectID={project}');
$config->my->task->dtable->fieldList['projectName']['group']    = 'project';
$config->my->task->dtable->fieldList['projectName']['sortType'] = true;
$config->my->task->dtable->fieldList['projectName']['show']     = true;

$config->my->task->dtable->fieldList['executionName']['name']     = 'executionName';
$config->my->task->dtable->fieldList['executionName']['title']    = $lang->task->execution;
$config->my->task->dtable->fieldList['executionName']['type']     = 'text';
$config->my->task->dtable->fieldList['executionName']['link']     = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={execution}');
$config->my->task->dtable->fieldList['executionName']['group']    = 'project';
$config->my->task->dtable->fieldList['executionName']['sortType'] = true;
$config->my->task->dtable->fieldList['executionName']['show']     = true;

$config->my->task->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->task->dtable->fieldList['openedBy']['title']    = $lang->task->openedByAB;
$config->my->task->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['openedBy']['group']    = 'user';
$config->my->task->dtable->fieldList['openedBy']['sortType'] = true;
$config->my->task->dtable->fieldList['openedBy']['show']     = true;

$config->my->task->dtable->fieldList['assignedTo']['name']     = 'assignedTo';
$config->my->task->dtable->fieldList['assignedTo']['title']    = $lang->task->assignedToAB;
$config->my->task->dtable->fieldList['assignedTo']['type']     = 'user';
$config->my->task->dtable->fieldList['assignedTo']['group']    = 'user';
$config->my->task->dtable->fieldList['assignedTo']['sortType'] = true;

$config->my->task->dtable->fieldList['finishedBy']['name']     = 'finishedBy';
$config->my->task->dtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->my->task->dtable->fieldList['finishedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['finishedBy']['group']    = 'user';
$config->my->task->dtable->fieldList['finishedBy']['sortType'] = true;

$config->my->task->dtable->fieldList['deadline']['name']     = 'deadline';
$config->my->task->dtable->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->my->task->dtable->fieldList['deadline']['type']     = 'date';
$config->my->task->dtable->fieldList['deadline']['group']    = 'deadline';
$config->my->task->dtable->fieldList['deadline']['sortType'] = true;

$config->my->task->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->task->dtable->fieldList['estimate']['title']    = $lang->task->estimateAB;
$config->my->task->dtable->fieldList['estimate']['type']     = 'number';
$config->my->task->dtable->fieldList['estimate']['group']    = 'deadline';
$config->my->task->dtable->fieldList['estimate']['sortType'] = true;

$config->my->task->dtable->fieldList['consumed']['name']     = 'consumed';
$config->my->task->dtable->fieldList['consumed']['title']    = $lang->task->consumedAB;
$config->my->task->dtable->fieldList['consumed']['type']     = 'number';
$config->my->task->dtable->fieldList['consumed']['group']    = 'deadline';
$config->my->task->dtable->fieldList['consumed']['sortType'] = true;

$config->my->task->dtable->fieldList['left']['name']     = 'left';
$config->my->task->dtable->fieldList['left']['title']    = $lang->task->leftAB;
$config->my->task->dtable->fieldList['left']['type']     = 'number';
$config->my->task->dtable->fieldList['left']['group']    = 'deadline';
$config->my->task->dtable->fieldList['left']['sortType'] = true;

$config->my->task->dtable->fieldList['closedBy']['title']    = $lang->task->closedBy;
$config->my->task->dtable->fieldList['closedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['closedBy']['sortType'] = true;
$config->my->task->dtable->fieldList['closedBy']['group']    = 6;

$config->my->task->dtable->fieldList['closedDate']['title']    = $lang->task->closedDate;
$config->my->task->dtable->fieldList['closedDate']['type']     = 'datetime';
$config->my->task->dtable->fieldList['closedDate']['sortType'] = true;
$config->my->task->dtable->fieldList['closedDate']['group']    = 6;

$config->my->task->dtable->fieldList['closedReason']['title']    = $lang->task->closedReason;
$config->my->task->dtable->fieldList['closedReason']['type']     = 'category';
$config->my->task->dtable->fieldList['closedReason']['map']      = $lang->task->reasonList;
$config->my->task->dtable->fieldList['closedReason']['sortType'] = true;
$config->my->task->dtable->fieldList['closedReason']['group']    = 6;

$config->my->task->dtable->fieldList['canceledBy']['title']    = $lang->task->canceledBy;
$config->my->task->dtable->fieldList['canceledBy']['type']     = 'user';
$config->my->task->dtable->fieldList['canceledBy']['sortType'] = true;
$config->my->task->dtable->fieldList['canceledBy']['group']    = 7;

$config->my->task->dtable->fieldList['canceledDate']['title']    = $lang->task->canceledDate;
$config->my->task->dtable->fieldList['canceledDate']['type']     = 'date';
$config->my->task->dtable->fieldList['canceledDate']['sortType'] = true;
$config->my->task->dtable->fieldList['canceledDate']['group']    = 7;

$config->my->task->dtable->fieldList['lastEditedBy']['title']    = $lang->task->lastEditedBy;
$config->my->task->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->my->task->dtable->fieldList['lastEditedBy']['group']    = 8;

$config->my->task->dtable->fieldList['lastEditedDate']['title']    = $lang->task->lastEditedDate;
$config->my->task->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->my->task->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->my->task->dtable->fieldList['lastEditedDate']['group']    = 8;

$config->my->task->dtable->fieldList['activatedDate']['title']    = $lang->task->activatedDate;
$config->my->task->dtable->fieldList['activatedDate']['type']     = 'date';
$config->my->task->dtable->fieldList['activatedDate']['sortType'] = true;
$config->my->task->dtable->fieldList['activatedDate']['group']    = 8;

$config->my->task->dtable->fieldList['actions']['name']     = 'actions';
$config->my->task->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->task->dtable->fieldList['actions']['type']     = 'actions';
$config->my->task->dtable->fieldList['actions']['sortType'] = false;
$config->my->task->dtable->fieldList['actions']['width']    = 180;
$config->my->task->dtable->fieldList['actions']['list']     = $config->my->task->actionList;
$config->my->task->dtable->fieldList['actions']['menu']     = array(array('confirmStoryChange'), array('start|restart', 'finish', 'close', 'record', 'edit', 'batchCreate'));

if($isEn)
{
    $config->my->task->dtable->fieldList['finishedBy']['width'] = 120;
    $config->my->task->dtable->fieldList['left']['width']       = 100;
    $config->my->task->dtable->fieldList['assignedTo']['width'] = 100;
    $config->my->task->dtable->fieldList['estimate']['width']   = 100;
}
