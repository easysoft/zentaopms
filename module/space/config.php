<?php
global $lang;
$config->setSpaceMenu = array('spaceSetting', 'pipeline', 'artifact');

$config->space->create = new stdclass();
$config->space->create->requiredFields = 'name,manager';
$config->space->edit = new stdclass();
$config->space->edit->requiredFields = 'name,manager';

$config->space->actionList = array();
$config->space->actionList['members']['icon']     = 'group';
$config->space->actionList['members']['text']     = $lang->space->members;
$config->space->actionList['members']['hint']     = $lang->space->members;
$config->space->actionList['members']['showText'] = true;
$config->space->actionList['members']['url']      = array('module' => 'space', 'method' => 'manageMembers', 'params' => 'spaceID={id}');

$config->space->actionList['group']['icon']     = 'lock';
$config->space->actionList['group']['text']     = $lang->space->group;
$config->space->actionList['group']['hint']     = $lang->space->group;
$config->space->actionList['group']['showText'] = true;
$config->space->actionList['group']['url']      = array('module' => 'space', 'method' => 'group', 'params' => 'spaceID={id}');

$config->space->actionList['edit']['icon']     = 'edit';
$config->space->actionList['edit']['text']     = $lang->edit;
$config->space->actionList['edit']['hint']     = $lang->edit;
$config->space->actionList['edit']['showText'] = true;
$config->space->actionList['edit']['url']      = array('module' => 'space', 'method' => 'edit', 'params' => 'id={id}');

$config->space->actionList['delete']['icon']         = 'trash';
$config->space->actionList['delete']['hint']         = $lang->delete;
$config->space->actionList['delete']['ajaxSubmit']   = true;
$config->space->actionList['delete']['data-confirm'] = array('message' => $lang->space->notice->confirmDeleteSpace, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->space->actionList['delete']['url']          = array('module' => 'space', 'method' => 'delete', 'params' => 'id={id}');

$config->space->actions = new stdclass();
$config->space->actions->view['mainActions']   = array('members', 'group');
$config->space->actions->view['suffixActions'] = array('edit', 'delete');
