<?php
global $lang;
$config->kanban = new stdclass();

$config->kanban->require = new stdclass();
$config->kanban->require->createregion = 'name';
$config->kanban->require->createlane   = 'name';
$config->kanban->require->createcolumn = 'name';

$config->kanban->actions = new stdclass();
$config->kanban->actions->view = array();
$config->kanban->actions->viewcard['mainActions']   = array('editCard', 'finishCard', 'activateCard', 'archiveCard', 'restoreCard');
$config->kanban->actions->viewcard['suffixActions'] = array('deleteCard');

$config->kanban->setwip        = new stdclass();
$config->kanban->setlane       = new stdclass();
$config->kanban->setColumn     = new stdclass();
$config->kanban->create        = new stdclass();
$config->kanban->edit          = new stdclass();
$config->kanban->createspace   = new stdclass();
$config->kanban->editspace     = new stdclass();
$config->kanban->createregion  = new stdclass();
$config->kanban->createcard    = new stdclass();
$config->kanban->editcard      = new stdclass();
$config->kanban->editregion    = new stdclass();
$config->kanban->splitcolumn   = new stdclass();

$config->kanban->setwip->requiredFields        = 'limit';
$config->kanban->setlane->requiredFields       = 'name,type';
$config->kanban->setColumn->requiredFields     = 'name';
$config->kanban->create->requiredFields        = 'space,name';
$config->kanban->edit->requiredFields          = 'space,name';
$config->kanban->createspace->requiredFields   = 'name,owner';
$config->kanban->editspace->requiredFields     = 'name,owner';
$config->kanban->createregion->requiredFields  = 'name';
$config->kanban->createcard->requiredFields    = 'name';
$config->kanban->editcard->requiredFields      = 'name';
$config->kanban->editregion->requiredFields    = 'name';
$config->kanban->splitcolumn->requiredFields   = 'name,limit';

$config->kanban->editor = new stdclass();
$config->kanban->editor->closespace    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->kanban->editor->activatespace = array('id' => 'comment', 'tools' => 'simpleTools');
$config->kanban->editor->createspace   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->editspace     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->create        = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->edit          = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->createcard    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->activate      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->kanban->editor->close         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->kanban->editor->editcard      = array('id' => 'desc', 'tools' => 'simpleTools');
$config->kanban->editor->viewcard      = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->kanban->editor->activatecard  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->kanban->fromType         = array('execution', 'productplan', 'release', 'build');
$config->kanban->executionField   = array('name', 'status', 'end', 'PM', 'type', 'deleted');
$config->kanban->productplanField = array('title', 'status', 'begin', 'end', 'deleted');
$config->kanban->releaseField     = array('name', 'status', 'date', 'deleted');
$config->kanban->buildField       = array('name', 'date', 'builder', 'deleted');

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

$config->kanban->laneColorList   = array('#3C4353', '#838A9D', '#476BDA', '#A65BD4', '#449C9D', '#D75557', '#CE814C', '#E7D057', '#73BE60', '#F6998A', '#8DA8ED', '#93DAF6', '#B9E78C', '#E58CE7', '#EDE590', '#CFB5FA');
$config->kanban->columnColorList = array('#333', '#2b519c', '#e48610', '#d2313d', '#2a9f23', '#777', '#d2691e', '#2e8b8b', '#2f8b58', '#4168e0', '#4b0082', '#f58072', '#ba55d3', '#6a8e22');
$config->kanban->cardColorList   = array('#fff', '#b10b0b', '#cfa227', '#2a5f29');

$config->kanban->batchCreate   = 10;
