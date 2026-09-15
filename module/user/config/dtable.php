<?php
global $app, $lang;

$app->loadLang('task');
if($config->edition != 'open') $app->loadLang('custom');

$isEn = $app->getClientLang() == 'en';

$config->user->execution = new stdclass();
$config->user->execution->dtable = new stdclass();
$config->user->execution->dtable->name['link'] = array('module' => 'execution', 'method' => 'view', 'params' => 'executionID={id}');

$config->user->task = new stdclass();
$config->user->task->dtable = new stdclass();
$config->user->task->dtable->defaultField = $config->user->defaultFields['task'];

$config->user->task->dtable->fieldList['id']['name']     = 'id';
$config->user->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->user->task->dtable->fieldList['id']['type']     = 'id';
$config->user->task->dtable->fieldList['id']['checkbox'] = false;
$config->user->task->dtable->fieldList['id']['sortType'] = true;

$config->user->task->dtable->fieldList['name']['name']         = 'name';
$config->user->task->dtable->fieldList['name']['title']        = $lang->task->name;
$config->user->task->dtable->fieldList['name']['type']         = 'title';
$config->user->task->dtable->fieldList['name']['nestedToggle'] = true;
$config->user->task->dtable->fieldList['name']['link']         = array('url' => array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}'), 'data-app' => 'execution');
$config->user->task->dtable->fieldList['name']['styleMap']     = array('--color-link' => 'color');
$config->user->task->dtable->fieldList['name']['fixed']        = 'left';
$config->user->task->dtable->fieldList['name']['data-toggle']  = 'modal';
$config->user->task->dtable->fieldList['name']['data-size']    = 'lg';
$config->user->task->dtable->fieldList['name']['sortType']     = true;
$config->user->task->dtable->fieldList['name']['show']         = true;

$config->user->task->dtable->fieldList['pri']['name']     = 'pri';
$config->user->task->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->user->task->dtable->fieldList['pri']['type']     = 'pri';
$config->user->task->dtable->fieldList['pri']['priList']  = $lang->task->priList;
$config->user->task->dtable->fieldList['pri']['group']    = 'pri';
$config->user->task->dtable->fieldList['pri']['sortType'] = true;

$config->user->task->dtable->fieldList['status']['name']      = 'status';
$config->user->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->user->task->dtable->fieldList['status']['type']      = 'status';
$config->user->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList + array('changed' => $lang->task->storyChange);
$config->user->task->dtable->fieldList['status']['group']     = 'pri';
$config->user->task->dtable->fieldList['status']['sortType']  = true;

$config->user->task->dtable->fieldList['project']['name']     = 'project';
$config->user->task->dtable->fieldList['project']['title']    = $lang->task->project;
$config->user->task->dtable->fieldList['project']['type']     = 'text';
$config->user->task->dtable->fieldList['project']['link']     = array('module' => 'project', 'method' => 'view', 'params' => 'projectID={project}');
$config->user->task->dtable->fieldList['project']['group']    = 'project';
$config->user->task->dtable->fieldList['project']['sortType'] = true;

$config->user->task->dtable->fieldList['execution']['name']     = 'execution';
$config->user->task->dtable->fieldList['execution']['title']    = $lang->task->execution;
$config->user->task->dtable->fieldList['execution']['type']     = 'text';
$config->user->task->dtable->fieldList['execution']['link']     = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={execution}');
$config->user->task->dtable->fieldList['execution']['group']    = 'project';
$config->user->task->dtable->fieldList['execution']['sortType'] = true;

$config->user->task->dtable->fieldList['type']['name']     = 'type';
$config->user->task->dtable->fieldList['type']['title']    = $lang->task->typeAB;
$config->user->task->dtable->fieldList['type']['type']     = 'category';
$config->user->task->dtable->fieldList['type']['map']      = $lang->task->typeList;
$config->user->task->dtable->fieldList['type']['group']    = 'pri';
$config->user->task->dtable->fieldList['type']['sortType'] = true;

$config->user->task->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->user->task->dtable->fieldList['openedBy']['title']    = $lang->task->openedByAB;
$config->user->task->dtable->fieldList['openedBy']['type']     = 'user';
$config->user->task->dtable->fieldList['openedBy']['group']    = 'user';
$config->user->task->dtable->fieldList['openedBy']['sortType'] = true;

$config->user->task->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->user->task->dtable->fieldList['openedDate']['title']    = $lang->task->openedDate;
$config->user->task->dtable->fieldList['openedDate']['type']     = 'date';
$config->user->task->dtable->fieldList['openedDate']['group']    = 'user';
$config->user->task->dtable->fieldList['openedDate']['sortType'] = true;

$config->user->task->dtable->fieldList['assignedTo']['name']     = 'assignedTo';
$config->user->task->dtable->fieldList['assignedTo']['title']    = $lang->task->assignedToAB;
$config->user->task->dtable->fieldList['assignedTo']['type']     = 'user';
$config->user->task->dtable->fieldList['assignedTo']['group']    = 'user';
$config->user->task->dtable->fieldList['assignedTo']['sortType'] = true;

$config->user->task->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->user->task->dtable->fieldList['assignedDate']['title']    = $lang->task->assignedDate;
$config->user->task->dtable->fieldList['assignedDate']['type']     = 'date';
$config->user->task->dtable->fieldList['assignedDate']['group']    = 'user';
$config->user->task->dtable->fieldList['assignedDate']['sortType'] = true;

$config->user->task->dtable->fieldList['finishedBy']['name']     = 'finishedBy';
$config->user->task->dtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->user->task->dtable->fieldList['finishedBy']['type']     = 'user';
$config->user->task->dtable->fieldList['finishedBy']['group']    = 'user';
$config->user->task->dtable->fieldList['finishedBy']['sortType'] = true;

$config->user->task->dtable->fieldList['estStarted']['name']     = 'estStarted';
$config->user->task->dtable->fieldList['estStarted']['title']    = $lang->task->estStarted;
$config->user->task->dtable->fieldList['estStarted']['type']     = 'date';
$config->user->task->dtable->fieldList['estStarted']['group']    = 'user';
$config->user->task->dtable->fieldList['estStarted']['sortType'] = true;

$config->user->task->dtable->fieldList['realStarted']['name']     = 'realStarted';
$config->user->task->dtable->fieldList['realStarted']['title']    = $lang->task->realStarted;
$config->user->task->dtable->fieldList['realStarted']['type']     = 'date';
$config->user->task->dtable->fieldList['realStarted']['group']    = 'user';
$config->user->task->dtable->fieldList['realStarted']['sortType'] = true;

$config->user->task->dtable->fieldList['finishedDate']['name']     = 'finishedDate';
$config->user->task->dtable->fieldList['finishedDate']['title']    = $lang->task->finishedDateAB;
$config->user->task->dtable->fieldList['finishedDate']['type']     = 'date';
$config->user->task->dtable->fieldList['finishedDate']['group']    = 'user';
$config->user->task->dtable->fieldList['finishedDate']['sortType'] = true;

$config->user->task->dtable->fieldList['deadline']['name']     = 'deadline';
$config->user->task->dtable->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->user->task->dtable->fieldList['deadline']['type']     = 'date';
$config->user->task->dtable->fieldList['deadline']['group']    = 'deadline';
$config->user->task->dtable->fieldList['deadline']['sortType'] = true;

$config->user->task->dtable->fieldList['estimate']['name']     = 'estimate';
$config->user->task->dtable->fieldList['estimate']['title']    = $lang->task->estimateAB;
$config->user->task->dtable->fieldList['estimate']['type']     = 'number';
$config->user->task->dtable->fieldList['estimate']['group']    = 'deadline';
$config->user->task->dtable->fieldList['estimate']['sortType'] = true;

$config->user->task->dtable->fieldList['consumed']['name']     = 'consumed';
$config->user->task->dtable->fieldList['consumed']['title']    = $lang->task->consumedAB;
$config->user->task->dtable->fieldList['consumed']['type']     = 'number';
$config->user->task->dtable->fieldList['consumed']['group']    = 'deadline';
$config->user->task->dtable->fieldList['consumed']['sortType'] = true;

$config->user->task->dtable->fieldList['left']['name']     = 'left';
$config->user->task->dtable->fieldList['left']['title']    = $lang->task->leftAB;
$config->user->task->dtable->fieldList['left']['type']     = 'number';
$config->user->task->dtable->fieldList['left']['group']    = 'deadline';
$config->user->task->dtable->fieldList['left']['sortType'] = true;

$config->user->task->dtable->fieldList['progress']['name']     = 'progress';
$config->user->task->dtable->fieldList['progress']['title']    = $lang->task->progressAB;
$config->user->task->dtable->fieldList['progress']['type']     = 'progress';
$config->user->task->dtable->fieldList['progress']['group']    = 'deadline';
$config->user->task->dtable->fieldList['progress']['sortType'] = false;

if($config->edition != 'open')
{
    $config->user->task->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
    $config->user->task->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
    $config->user->task->dtable->fieldList['relatedObject']['type']        = 'text';
    $config->user->task->dtable->fieldList['relatedObject']['group']       = 'deadline';
    $config->user->task->dtable->fieldList['relatedObject']['sortType']    = false;
    $config->user->task->dtable->fieldList['relatedObject']['width']       = '70';
    $config->user->task->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
    $config->user->task->dtable->fieldList['relatedObject']['data-size']   = 'lg';
    $config->user->task->dtable->fieldList['relatedObject']['flex']        = false;
    $config->user->task->dtable->fieldList['relatedObject']['align']       = 'center';
    if($isEn) $config->user->task->dtable->fieldList['relatedObject']['width'] = '120';
}

$config->user->task->dtable->fieldList['story']['name']     = 'story';
$config->user->task->dtable->fieldList['story']['title']    = $lang->task->storyAB;
$config->user->task->dtable->fieldList['story']['type']     = 'desc';
$config->user->task->dtable->fieldList['story']['group']    = 9;
$config->user->task->dtable->fieldList['story']['sortType'] = true;
$config->user->task->dtable->fieldList['story']['link']     = array('url' => array('module' => 'story', 'method' => 'view', 'params' => 'id={rawStory}'), 'className' => 'text-inherit');

$config->user->task->dtable->fieldList['keywords']['name']     = 'keywords';
$config->user->task->dtable->fieldList['keywords']['title']    = $lang->task->keywords;
$config->user->task->dtable->fieldList['keywords']['type']     = 'text';
$config->user->task->dtable->fieldList['keywords']['group']    = 9;
$config->user->task->dtable->fieldList['keywords']['sortType'] = true;

$config->user->task->dtable->fieldList['mailto']['name']      = 'mailto';
$config->user->task->dtable->fieldList['mailto']['title']     = $lang->task->mailto;
$config->user->task->dtable->fieldList['mailto']['type']      = 'text';
$config->user->task->dtable->fieldList['mailto']['group']     = 9;
$config->user->task->dtable->fieldList['mailto']['sortType']  = true;
$config->user->task->dtable->fieldList['mailto']['delimiter'] = ',';

$config->user->task->dtable->fieldList['closedBy']['title']    = $lang->task->closedBy;
$config->user->task->dtable->fieldList['closedBy']['type']     = 'user';
$config->user->task->dtable->fieldList['closedBy']['sortType'] = true;
$config->user->task->dtable->fieldList['closedBy']['group']    = 6;

$config->user->task->dtable->fieldList['closedDate']['title']    = $lang->task->closedDate;
$config->user->task->dtable->fieldList['closedDate']['type']     = 'datetime';
$config->user->task->dtable->fieldList['closedDate']['sortType'] = true;
$config->user->task->dtable->fieldList['closedDate']['group']    = 6;

$config->user->task->dtable->fieldList['closedReason']['title']    = $lang->task->closedReason;
$config->user->task->dtable->fieldList['closedReason']['type']     = 'category';
$config->user->task->dtable->fieldList['closedReason']['map']      = $lang->task->reasonList;
$config->user->task->dtable->fieldList['closedReason']['sortType'] = true;
$config->user->task->dtable->fieldList['closedReason']['group']    = 6;

$config->user->task->dtable->fieldList['canceledBy']['title']    = $lang->task->canceledBy;
$config->user->task->dtable->fieldList['canceledBy']['type']     = 'user';
$config->user->task->dtable->fieldList['canceledBy']['sortType'] = true;
$config->user->task->dtable->fieldList['canceledBy']['group']    = 7;

$config->user->task->dtable->fieldList['canceledDate']['title']    = $lang->task->canceledDate;
$config->user->task->dtable->fieldList['canceledDate']['type']     = 'date';
$config->user->task->dtable->fieldList['canceledDate']['sortType'] = true;
$config->user->task->dtable->fieldList['canceledDate']['group']    = 7;

$config->user->task->dtable->fieldList['lastEditedBy']['title']    = $lang->task->lastEditedBy;
$config->user->task->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->user->task->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->user->task->dtable->fieldList['lastEditedBy']['group']    = 8;

$config->user->task->dtable->fieldList['lastEditedDate']['title']    = $lang->task->lastEditedDate;
$config->user->task->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->user->task->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->user->task->dtable->fieldList['lastEditedDate']['group']    = 8;

$config->user->task->dtable->fieldList['activatedDate']['title']    = $lang->task->activatedDate;
$config->user->task->dtable->fieldList['activatedDate']['type']     = 'date';
$config->user->task->dtable->fieldList['activatedDate']['sortType'] = true;
$config->user->task->dtable->fieldList['activatedDate']['group']    = 8;

if($isEn)
{
    $config->user->task->dtable->fieldList['finishedBy']['width'] = 120;
    $config->user->task->dtable->fieldList['left']['width']       = 100;
    $config->user->task->dtable->fieldList['assignedTo']['width'] = 100;
    $config->user->task->dtable->fieldList['estimate']['width']   = 100;
}

foreach($config->user->task->dtable->fieldList as $field => $fieldConfig)
{
    $config->user->task->dtable->fieldList[$field]['show'] = in_array($field, $config->user->task->dtable->defaultField);
}
$config->user->task->dtable->fieldList['name']['show'] = true;
