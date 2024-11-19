<?php
global $lang;
$config->system = new stdclass();

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
