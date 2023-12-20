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
$config->productplan->defaultFields['story']     = array('id', 'title', 'module', 'pri', 'status', 'openedBy', 'assignedTo', 'estimate', 'stage', 'actions');
$config->productplan->defaultFields['bug']       = array('id', 'title', 'pri', 'status', 'openedBy', 'assignedTo', 'actions');
$config->productplan->defaultFields['linkStory'] = array('id', 'pri', 'plan', 'module', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage');
$config->productplan->defaultFields['linkBug']   = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'status');

$config->productplan->actionList['start']['icon']         = 'play';
$config->productplan->actionList['start']['hint']         = $lang->productplan->start;
$config->productplan->actionList['start']['text']         = $lang->productplan->start;
$config->productplan->actionList['start']['url']          = helper::createLink('productplan', 'start', 'planID={id}');
$config->productplan->actionList['start']['data-confirm'] = $lang->productplan->confirmStart;
$config->productplan->actionList['start']['className']    = 'ajax-submit';

$config->productplan->actionList['finish']['icon']         = 'checked';
$config->productplan->actionList['finish']['hint']         = $lang->productplan->finish;
$config->productplan->actionList['finish']['text']         = $lang->productplan->finish;
$config->productplan->actionList['finish']['url']          = helper::createLink('productplan', 'finish', 'planID={id}');
$config->productplan->actionList['finish']['data-confirm'] = $lang->productplan->confirmFinish;
$config->productplan->actionList['finish']['innerClass']   = 'ajax-submit';

$config->productplan->actionList['close']['icon']        = 'off';
$config->productplan->actionList['close']['hint']        = $lang->productplan->close;
$config->productplan->actionList['close']['text']        = $lang->productplan->close;
$config->productplan->actionList['close']['url']         = helper::createLink('productplan', 'close', 'planID={id}');
$config->productplan->actionList['close']['data-toggle'] = 'modal';

$config->productplan->actionList['activate']['icon']         = 'magic';
$config->productplan->actionList['activate']['hint']         = $lang->productplan->activate;
$config->productplan->actionList['activate']['text']         = $lang->productplan->activate;
$config->productplan->actionList['activate']['url']          = helper::createLink('productplan', 'activate', 'planID={id}');
$config->productplan->actionList['activate']['data-confirm'] = $lang->productplan->confirmActivate;
$config->productplan->actionList['activate']['className']    = 'ajax-submit';

$config->productplan->actionList['createExecution']['icon']        = 'plus';
$config->productplan->actionList['createExecution']['hint']        = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['text']        = $lang->productplan->createExecution;
$config->productplan->actionList['createExecution']['data-target'] = '#createExecutionModal';
$config->productplan->actionList['createExecution']['data-toggle'] = 'modal';
$config->productplan->actionList['createExecution']['url']         = array('module' => 'execution', 'method' => 'create');

$config->productplan->actionList['linkStory']['icon'] = 'link';
$config->productplan->actionList['linkStory']['hint'] = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['text'] = $lang->productplan->linkStory;
$config->productplan->actionList['linkStory']['url']  = helper::createLink($app->rawModule, 'view', 'planID={id}&type=story&orderBy=id_desc&link=true');

$config->productplan->actionList['linkBug']['icon'] = 'bug';
$config->productplan->actionList['linkBug']['hint'] = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['text'] = $lang->productplan->linkBug;
$config->productplan->actionList['linkBug']['url']  = helper::createLink($app->rawModule, 'view', 'planID={id}&type=bug&orderBy=id_desc&link=true');

$config->productplan->actionList['edit']['icon'] = 'edit';
$config->productplan->actionList['edit']['hint'] = $lang->productplan->edit;
$config->productplan->actionList['edit']['text'] = $lang->productplan->edit;
$config->productplan->actionList['edit']['url']  = helper::createLink($app->rawModule, 'edit', 'planID={id}');

$config->productplan->actionList['create']['icon'] = 'split';
$config->productplan->actionList['create']['hint'] = $lang->productplan->createChildren;
$config->productplan->actionList['create']['text'] = $lang->productplan->createChildren;
$config->productplan->actionList['create']['url']  = helper::createLink($app->rawModule, 'create', 'product={product}&branch={branch}&parent={id}');

$config->productplan->actionList['delete']['icon']         = 'trash';
$config->productplan->actionList['delete']['hint']         = $lang->productplan->delete;
$config->productplan->actionList['delete']['text']         = $lang->productplan->delete;
$config->productplan->actionList['delete']['url']          = helper::createLink('productplan', 'delete', 'planID={id}');
$config->productplan->actionList['delete']['data-confirm'] = $lang->productplan->confirmDelete;
$config->productplan->actionList['delete']['innerClass']   = 'ajax-submit';

$config->productplan->actionList['unlinkBug']['icon'] = 'unlink';
$config->productplan->actionList['unlinkBug']['hint'] = $lang->productplan->unlinkBug;
$config->productplan->actionList['unlinkBug']['url']  = 'javascript:unlinkObject("bug", "{id}")';

$config->productplan->actionList['unlinkStory']['icon'] = 'unlink';
$config->productplan->actionList['unlinkStory']['hint'] = $lang->productplan->unlinkStory;
$config->productplan->actionList['unlinkStory']['url']  = 'javascript:unlinkObject("story", "{id}")';
