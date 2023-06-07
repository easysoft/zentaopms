<?php
$config->my = new stdclass();
$config->my->editprofile = new stdclass();
$config->my->editprofile->requiredFields = 'account,realname';

$config->my->dynamicCounts = 14;
$config->my->todoCounts    = 10;
$config->my->taskCounts    = 10;
$config->my->bugCounts     = 10;
$config->my->storyCounts   = 10;

$config->my->oaObjectType = 'attend,leave,makeup,overtime,lieu';

$config->mobile = new stdclass();
$config->mobile->todoBar  = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'all');
$config->mobile->taskBar  = array('assignedTo', 'openedBy');
$config->mobile->bugBar   = array('assignedTo', 'openedBy', 'resolvedBy');
$config->mobile->storyBar = array('assignedTo', 'openedBy', 'reviewedBy');

global $lang,$app;
$app->loadLang('todo');

$config->my->todo = new stdclass();
$config->my->todo->actionList = array();
$config->my->todo->actionList['start']['icon'] = 'play';
$config->my->todo->actionList['start']['text'] = $lang->todo->start;
$config->my->todo->actionList['start']['hint'] = $lang->todo->start;
$config->my->todo->actionList['start']['url']  = helper::createLink('todo', 'start', 'todoID={id}');

$config->my->todo->actionList['activate']['icon'] = 'magic';
$config->my->todo->actionList['activate']['text'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['hint'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['url']  = helper::createLink('todo', 'activate', 'todoID={id}');

$config->my->todo->actionList['close']['icon'] = 'off';
$config->my->todo->actionList['close']['text'] = $lang->todo->close;
$config->my->todo->actionList['close']['hint'] = $lang->todo->close;
$config->my->todo->actionList['close']['url']  = helper::createLink('todo', 'close', 'todoID={id}');

$config->my->todo->actionList['assignTo']['icon']        = 'hand-right';
$config->my->todo->actionList['assignTo']['text']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['hint']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['url']         = helper::createLink('todo', 'assignTo', 'todoID={id}');
$config->my->todo->actionList['assignTo']['data-toggle'] = 'modal';

$config->my->todo->actionList['finish']['icon'] = 'checked';
$config->my->todo->actionList['finish']['text'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['hint'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['url']  = helper::createLink('todo', 'finish', 'todoID={id}');

$config->my->todo->actionList['edit']['icon']        = 'edit';
$config->my->todo->actionList['edit']['text']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['hint']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['url']         = helper::createLink('todo', 'edit', 'todoID={id}');
$config->my->todo->actionList['edit']['data-toggle'] = 'modal';

$config->my->todo->actionList['delete']['icon']        = 'trash';
$config->my->todo->actionList['delete']['text']        = $lang->todo->delete;
$config->my->todo->actionList['delete']['hint']        = $lang->todo->delete;
$config->my->todo->actionList['delete']['url']         = helper::createLink('todo', 'delete', 'todoID={id}&confirm=yes');

$config->my->audit = new stdclass();
$config->my->audit->actionList = array();
$config->my->audit->actionList['review']['icon']        = 'glasses';
$config->my->audit->actionList['review']['text']        = $lang->review->common;
$config->my->audit->actionList['review']['hint']        = $lang->review->common;
$config->my->audit->actionList['review']['data-toggle'] = 'modal';
