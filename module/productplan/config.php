<?php
$config->productplan = new stdclass();
$config->productplan->create = new stdclass();
$config->productplan->edit   = new stdclass();
$config->productplan->create->requiredFields = 'title';
$config->productplan->edit->requiredFields   = 'title';

$config->productplan->editor = new stdclass();
$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->start  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->productplan->editor->view   = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->productplan->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$config->productplan->future = '2030-01-01';

global $app, $lang;
$app->loadLang('productplan');
$config->productplan->search['module'] = 'productplan';
$config->productplan->browse = new stdclass();

$config->productplan->search['fields']['title']  = $lang->productplan->title;
$config->productplan->search['fields']['id']     = $lang->productplan->id;
$config->productplan->search['fields']['branch'] = $lang->productplan->branch;
$config->productplan->search['fields']['status'] = $lang->productplan->status;
$config->productplan->search['fields']['begin']  = $lang->productplan->begin;
$config->productplan->search['fields']['end']    = $lang->productplan->end;

$config->productplan->search['params']['id']     = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->productplan->search['params']['title']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->productplan->search['params']['branch'] = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->productplan->search['params']['status'] = array('operator' => '=',       'control' => 'select', 'values' => $lang->productplan->statusList);
$config->productplan->search['params']['begin']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->productplan->search['params']['end']    = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');

$config->productplan->actionList['start']['icon']        = 'play';
$config->productplan->actionList['start']['hint']        = $lang->productplan->start;
$config->productplan->actionList['start']['text']        = $lang->productplan->start;
$config->productplan->actionList['start']['url']         = helper::createLink('productplan', 'start', 'productplanID={id}', '', true);
$config->productplan->actionList['start']['data-toggle'] = 'modal';

$config->productplan->actionList['createExecution']['icon']        = 'plus';
$config->productplan->actionList['createExecution']['hint']        = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['text']        = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['data-toggle'] = 'modal';

$config->productplan->actionList['linkStory']['icon']        = 'link';
$config->productplan->actionList['linkStory']['hint']        = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['text']        = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['data-toggle'] = 'modal';

$config->productplan->actionList['linkBug']['icon']        = 'bug';
$config->productplan->actionList['linkBug']['hint']        = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['text']        = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['data-toggle'] = 'modal';

$config->productplan->actionList['edit']['icon']  = 'edit';
$config->productplan->actionList['edit']['hint']  = $lang->productplan->edit;
$config->productplan->actionList['edit']['text']  = $lang->productplan->edit;
$config->productplan->actionList['edit']['url']   = helper::createLink('productplan', 'edit', 'productplanID={id}');

$config->productplan->actionList['unlinkBug']['icon'] = 'unlink';
$config->productplan->actionList['unlinkBug']['hint'] = $lang->productplan->unlinkBug;
$config->productplan->actionList['unlinkBug']['url']  = 'javascript:unlinkObject("bug", "{id}")';

$config->productplan->actionList['unlinkStory']['icon'] = 'unlink';
$config->productplan->actionList['unlinkStory']['hint'] = $lang->productplan->unlinkStory;
$config->productplan->actionList['unlinkStory']['url']  = 'javascript:unlinkObject("story", "{id}")';

$config->productplan->defaultFields['story']     = array('id', 'title', 'module', 'pri', 'status', 'openedBy', 'assignedTo', 'estimate', 'stage', 'actions');
$config->productplan->defaultFields['bug']       = array('id', 'title', 'pri', 'status', 'openedBy', 'assignedTo', 'actions');
$config->productplan->defaultFields['linkStory'] = array('id', 'pri', 'plan', 'module', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage');
$config->productplan->defaultFields['linkBug']   = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'status');
