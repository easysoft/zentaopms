<?php
global $lang;
$config->kanban = new stdclass();

$config->kanban->setwip  = new stdclass();
$config->kanban->setlane = new stdclass();
$config->kanban->setwip->requiredFields  = 'limit';
$config->kanban->setlane->requiredFields = 'name,type';

$config->kanban->default = new stdclass();
$config->kanban->default->story  = new stdclass();
$config->kanban->default->story->name  = $lang->SRCommon;
$config->kanban->default->story->color = '#7ec5ff';
$config->kanban->default->story->order = '5';

$config->kanban->default->bug = new stdclass();
$config->kanban->default->bug->name  = $lang->bug->common;
$config->kanban->default->bug->color = '#ba55d3';
$config->kanban->default->bug->order = '10';

$config->kanban->default->task = new stdclass();
$config->kanban->default->task->name  = $lang->task->common;
$config->kanban->default->task->color = '#4169e1';
$config->kanban->default->task->order = '15';

$config->kanban->storyColumnStageList = array();
$config->kanban->storyColumnStageList['backlog']    = 'projected';
$config->kanban->storyColumnStageList['ready']      = 'projected';
$config->kanban->storyColumnStageList['developing'] = 'developing';
$config->kanban->storyColumnStageList['developed']  = 'developed';
$config->kanban->storyColumnStageList['testing']    = 'testing';
$config->kanban->storyColumnStageList['tested']     = 'tested';
$config->kanban->storyColumnStageList['verified']   = 'verified';
$config->kanban->storyColumnStageList['released']   = 'released';
$config->kanban->storyColumnStageList['closed']     = 'closed';

$config->kanban->storyColumnStatusList = array();
$config->kanban->storyColumnStatusList['backlog']    = 'active';
$config->kanban->storyColumnStatusList['ready']      = 'active';
$config->kanban->storyColumnStatusList['developing'] = 'active';
$config->kanban->storyColumnStatusList['developed']  = 'active';
$config->kanban->storyColumnStatusList['testing']    = 'active';
$config->kanban->storyColumnStatusList['tested']     = 'active';
$config->kanban->storyColumnStatusList['verified']   = 'active';
$config->kanban->storyColumnStatusList['released']   = 'active';
$config->kanban->storyColumnStatusList['closed']     = 'closed';

$config->kanban->bugColumnStatusList = array();
$config->kanban->bugColumnStatusList['unconfirmed'] = 'active';
$config->kanban->bugColumnStatusList['confirmed']   = 'active';
$config->kanban->bugColumnStatusList['fixing']      = 'active';
$config->kanban->bugColumnStatusList['fixed']       = 'resolved';
$config->kanban->bugColumnStatusList['testing']     = 'resolved';
$config->kanban->bugColumnStatusList['tested']      = 'resolved';
$config->kanban->bugColumnStatusList['closed']      = 'closed';

$config->kanban->taskColumnStatusList = array();
$config->kanban->taskColumnStatusList['wait']       = 'wait';
$config->kanban->taskColumnStatusList['developing'] = 'doing';
$config->kanban->taskColumnStatusList['developed']  = 'done';
$config->kanban->taskColumnStatusList['pause']      = 'pause';
$config->kanban->taskColumnStatusList['canceled']   = 'cancel';
$config->kanban->taskColumnStatusList['closed']     = 'closed';

$config->kanban->laneColorList = array('#7ec5ff', '#333', '#2b529c', '#e48600', '#d2323d', '#229f24', '#777', '#d2691e', '#008b8b', '#2e8b57', '#4169e1', '#4b0082', '#fa8072', '#ba55d3', '#2e8b57', '#6b8e23');
