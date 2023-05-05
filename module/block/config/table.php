<?php
global $lang, $app;
$app->loadLang('project');
$app->loadLang('task');
$config->block->dtable = new stdclass();
$config->block->dtable->project = new stdclass();
$config->block->dtable->project->fieldList['name']['name']            = 'name';
$config->block->dtable->project->fieldList['name']['title']           = $lang->project->name;
$config->block->dtable->project->fieldList['name']['flex']            = 1;
$config->block->dtable->project->fieldList['name']['align']           = 'center';
$config->block->dtable->project->fieldList['name']['type']            = 'link';
$config->block->dtable->project->fieldList['name']['sortType']        = true;

$config->block->dtable->project->fieldList['PM']['name']              = 'PM';
$config->block->dtable->project->fieldList['PM']['title']             = $lang->project->PM;
$config->block->dtable->project->fieldList['PM']['width']             = 80;
$config->block->dtable->project->fieldList['PM']['align']             = 'center';
$config->block->dtable->project->fieldList['PM']['sortType']          = true;

$config->block->dtable->project->fieldList['status']['name']          = 'status';
$config->block->dtable->project->fieldList['status']['title']         = $lang->project->status;
$config->block->dtable->project->fieldList['status']['width']         = 80;
$config->block->dtable->project->fieldList['status']['align']         = 'center';
$config->block->dtable->project->fieldList['status']['sortType']      = true;

$config->block->dtable->project->fieldList['teamCount']['name']       = 'teamCount';
$config->block->dtable->project->fieldList['teamCount']['title']      = $lang->project->teamCount;
$config->block->dtable->project->fieldList['teamCount']['width']      = 80;
$config->block->dtable->project->fieldList['teamCount']['align']      = 'center';
$config->block->dtable->project->fieldList['teamCount']['sortType']   = true;

$config->block->dtable->project->fieldList['consumed']['name']        = 'consumed';
$config->block->dtable->project->fieldList['consumed']['title']       = $lang->task->consumed;
$config->block->dtable->project->fieldList['consumed']['width']       = 80;
$config->block->dtable->project->fieldList['consumed']['align']       = 'center';
$config->block->dtable->project->fieldList['consumed']['sortType']    = true;

$config->block->dtable->project->fieldList['budget']['name']          = 'budget';
$config->block->dtable->project->fieldList['budget']['title']         = $lang->project->budget;
$config->block->dtable->project->fieldList['budget']['width']         = 80;
$config->block->dtable->project->fieldList['budget']['align']         = 'center';
$config->block->dtable->project->fieldList['budget']['sortType']      = true;

$config->block->dtable->project->fieldList['leftStories']['name']     = 'leftStories';
$config->block->dtable->project->fieldList['leftStories']['title']    = $lang->project->leftStories;
$config->block->dtable->project->fieldList['leftStories']['width']    = 80;
$config->block->dtable->project->fieldList['leftStories']['align']    = 'center';
$config->block->dtable->project->fieldList['leftStories']['sortType'] = true;

$config->block->dtable->project->fieldList['leftTasks']['name']       = 'leftTasks';
$config->block->dtable->project->fieldList['leftTasks']['title']      = $lang->project->leftTasks;
$config->block->dtable->project->fieldList['leftTasks']['width']      = 80;
$config->block->dtable->project->fieldList['leftTasks']['align']      = 'center';
$config->block->dtable->project->fieldList['leftTasks']['sortType']   = true;

$config->block->dtable->project->fieldList['leftBugs']['name']        = 'leftBugs';
$config->block->dtable->project->fieldList['leftBugs']['title']       = $lang->project->leftBugs;
$config->block->dtable->project->fieldList['leftBugs']['width']       = 80;
$config->block->dtable->project->fieldList['leftBugs']['align']       = 'center';
$config->block->dtable->project->fieldList['leftBugs']['sortType']    = true;
