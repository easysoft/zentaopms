<?php
global $lang;

$config->branch = new stdclass();
$config->branch->create = new stdclass();
$config->branch->edit   = new stdclass();

$config->branch->create->requiredFields = 'name';
$config->branch->edit->requiredFields   = 'name,status';

$config->branch->actionList['close']['icon'] = 'off';
$config->branch->actionList['close']['hint'] = $lang->branch->close;
$config->branch->actionList['close']['text'] = $lang->branch->close;
$config->branch->actionList['close']['url']  = 'javascript:changeStatus("{id}", "close")';

$config->branch->actionList['activate']['icon'] = 'magic';
$config->branch->actionList['activate']['hint'] = $lang->branch->activate;
$config->branch->actionList['activate']['text'] = $lang->branch->activate;
$config->branch->actionList['activate']['url']  = 'javascript:changeStatus("{id}", "active")';

$config->branch->actionList['edit']['icon'] = 'edit';
$config->branch->actionList['edit']['hint'] = $lang->branch->edit;
$config->branch->actionList['edit']['url']  = helper::createLink('branch', 'edit', 'branchID={id}');
