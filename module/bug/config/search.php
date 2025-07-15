<?php
global $lang;
$config->bug->search['module']                   = 'bug';
$config->bug->search['fields']['title']          = $lang->bug->title;
$config->bug->search['fields']['module']         = $lang->bug->module;
$config->bug->search['fields']['keywords']       = $lang->bug->keywords;
$config->bug->search['fields']['steps']          = $lang->bug->steps;
$config->bug->search['fields']['assignedTo']     = $lang->bug->assignedTo;
$config->bug->search['fields']['resolvedBy']     = $lang->bug->resolvedBy;

$config->bug->search['fields']['status']         = $lang->bug->status;
$config->bug->search['fields']['confirmed']      = $lang->bug->confirmed;
$config->bug->search['fields']['story']          = $lang->bug->story;

$config->bug->search['fields']['project']        = $lang->bug->project;
$config->bug->search['fields']['product']        = $lang->bug->product;
$config->bug->search['fields']['branch']         = '';
$config->bug->search['fields']['plan']           = $lang->bug->plan;
$config->bug->search['fields']['id']             = $lang->bug->id;
$config->bug->search['fields']['execution']      = $lang->bug->execution;

$config->bug->search['fields']['severity']       = $lang->bug->severity;
$config->bug->search['fields']['pri']            = $lang->bug->pri;
$config->bug->search['fields']['type']           = $lang->bug->type;
$config->bug->search['fields']['os']             = $lang->bug->os;
$config->bug->search['fields']['browser']        = $lang->bug->browser;
$config->bug->search['fields']['resolution']     = $lang->bug->resolution;

$config->bug->search['fields']['activatedCount'] = $lang->bug->activatedCount;

$config->bug->search['fields']['toTask']         = $lang->bug->toTask;
$config->bug->search['fields']['toStory']        = $lang->bug->toStory;

$config->bug->search['fields']['openedBy']       = $lang->bug->openedBy;
$config->bug->search['fields']['closedBy']       = $lang->bug->closedBy;
$config->bug->search['fields']['lastEditedBy']   = $lang->bug->abbr->lastEditedBy;

if(in_array($config->edition, array('max', 'ipd')))
{
    $config->bug->search['fields']['injection']    = $lang->bug->injection;
    $config->bug->search['fields']['identify']     = $lang->bug->identify;
}

$config->bug->search['fields']['mailto']         = $lang->bug->mailto;
$config->bug->search['fields']['openedBuild']    = $lang->bug->openedBuild;
$config->bug->search['fields']['resolvedBuild']  = $lang->bug->resolvedBuild;

$config->bug->search['fields']['openedDate']     = $lang->bug->openedDate;
$config->bug->search['fields']['assignedDate']   = $lang->bug->assignedDate;
$config->bug->search['fields']['resolvedDate']   = $lang->bug->resolvedDate;
$config->bug->search['fields']['closedDate']     = $lang->bug->closedDate;
$config->bug->search['fields']['lastEditedDate'] = $lang->bug->abbr->lastEditedDate;
$config->bug->search['fields']['deadline']       = $lang->bug->deadline;
$config->bug->search['fields']['activatedDate']  = $lang->bug->activatedDate;

$config->bug->search['params']['title']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['keywords']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['steps']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['assignedTo']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['resolvedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->bug->search['params']['status']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->statusList);
$config->bug->search['params']['confirmed']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->confirmedList);
$config->bug->search['params']['story']         = array('operator' => 'include', 'control' => 'input',  'values' => '');

$config->bug->search['params']['project']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->bug->search['params']['product']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['branch']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['plan']          = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['module']        = array('operator' => 'belong',  'control' => 'select', 'values' => 'modules');
$config->bug->search['params']['execution']     = array('operator' => '=',       'control' => 'select', 'values' => 'executions');
$config->bug->search['params']['severity']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->severityList);
$config->bug->search['params']['pri']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->priList);
$config->bug->search['params']['type']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->typeList);
$config->bug->search['params']['os']            = array('operator' => 'include', 'control' => 'select', 'values' => $lang->bug->osList);
$config->bug->search['params']['browser']       = array('operator' => 'include', 'control' => 'select', 'values' => $lang->bug->browserList);
$config->bug->search['params']['resolution']    = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->resolutionList);
$config->bug->search['params']['activatedCount']= array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->bug->search['params']['toTask']        = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->bug->search['params']['toStory']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->bug->search['params']['openedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['closedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['lastEditedBy']  = array('operator' => '=',       'control' => 'select', 'values' => 'users');

if(in_array($config->edition, array('max', 'ipd')))
{
    $config->bug->search['params']['injection']   = array('operator' => '=', 'control' => 'select', 'values' => $lang->bug->injectionList);
    $config->bug->search['params']['identify']    = array('operator' => '=', 'control' => 'select', 'values' => $lang->bug->identifyList);
}

$config->bug->search['params']['mailto']        = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->bug->search['params']['openedBuild']   = array('operator' => 'include', 'control' => 'select', 'values' => 'builds');
$config->bug->search['params']['resolvedBuild'] = array('operator' => '=',       'control' => 'select', 'values' => 'builds');
$config->bug->search['params']['openedDate']    = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['assignedDate']  = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['resolvedDate']  = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['closedDate']    = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['lastEditedDate']= array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['deadline']      = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->bug->search['params']['activatedDate'] = array('operator' => '=',       'control' => 'date',  'values' => '');
