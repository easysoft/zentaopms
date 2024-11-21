<?php
global $lang;
$config->system = new stdclass();

$config->system->create = new stdclass();
$config->system->create->requiredFields = 'name';

$config->system->edit = new stdclass();
$config->system->edit->requiredFields   = 'name';

$config->system->groupPrivs = array();
$config->system->groupPrivs['dashboard']     = 'backup|index';
$config->system->groupPrivs['deletebackup']  = 'backup|delete';
$config->system->groupPrivs['restorebackup'] = 'backup|restore';

$config->system->actionList = array();
$config->system->actionList['active']['icon']         = 'arrow-up';
$config->system->actionList['active']['text']         = $lang->system->active;
$config->system->actionList['active']['hint']         = $lang->system->active;
$config->system->actionList['active']['showText']     = true;
$config->system->actionList['active']['ajaxSubmit']   = true;
$config->system->actionList['active']['data-confirm'] = array('message' => $lang->system->confirmActive, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->system->actionList['active']['url']          = array('module' => 'system', 'method' => 'active', 'params' => 'id={id}');

$config->system->actionList['inactive']['icon']         = 'arrow-down';
$config->system->actionList['inactive']['text']         = $lang->system->inactive;
$config->system->actionList['inactive']['hint']         = $lang->system->inactive;
$config->system->actionList['inactive']['showText']     = true;
$config->system->actionList['inactive']['ajaxSubmit']   = true;
$config->system->actionList['inactive']['data-confirm'] = array('message' => $lang->system->confirmInactive, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->system->actionList['inactive']['url']          = array('module' => 'system', 'method' => 'inactive', 'params' => 'id={id}');

$config->system->actionList['edit']['icon']        = 'edit';
$config->system->actionList['edit']['text']        = $lang->edit;
$config->system->actionList['edit']['hint']        = $lang->edit;
$config->system->actionList['edit']['showText']    = true;
$config->system->actionList['edit']['url']         = array('module' => 'system', 'method' => 'edit', 'params' => 'id={id}');
$config->system->actionList['edit']['data-toggle'] = 'modal';

$config->system->actionList['delete']['icon']         = 'trash';
$config->system->actionList['delete']['text']         = $lang->delete;
$config->system->actionList['delete']['hint']         = $lang->delete;
$config->system->actionList['delete']['showText']     = true;
$config->system->actionList['delete']['ajaxSubmit']   = true;
$config->system->actionList['delete']['data-confirm'] = array('message' => $lang->system->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->system->actionList['delete']['url']          = array('module' => 'system', 'method' => 'delete', 'params' => 'id={id}');
