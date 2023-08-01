<?php
$config->action->actionList = array();
$config->action->actionList['undelete']['icon'] = 'back';
$config->action->actionList['undelete']['text'] = $lang->action->undelete;
$config->action->actionList['undelete']['hint'] = $lang->action->undelete;
$config->action->actionList['undelete']['url']  = array('module' => 'action', 'method' => 'undelete', 'params' => 'actionid={id}');

$config->action->actionList['hideone']['icon'] = 'eye-off';
$config->action->actionList['hideone']['text'] = $lang->action->hideOne;
$config->action->actionList['hideone']['hint'] = $lang->action->hideOne;
$config->action->actionList['hideone']['url']  = array('module' => 'action', 'method' => 'hideone', 'params' => 'actionid={id}');
