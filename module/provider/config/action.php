<?php
global $app, $lang;
$config->provider->actionList = array();
$config->provider->actionList['edit']['icon'] = 'edit';
$config->provider->actionList['edit']['text'] = $lang->provider->edit;
$config->provider->actionList['edit']['hint'] = $lang->provider->edit;
$config->provider->actionList['edit']['url']  = array('module' => 'provider', 'method' => 'edit', 'params' => 'id={id}');

$config->provider->actionList['delete']['icon']         = 'trash';
$config->provider->actionList['delete']['text']         = $lang->provider->delete;
$config->provider->actionList['delete']['hint']         = $lang->provider->delete;
$config->provider->actionList['delete']['url']          = array('module' => 'provider', 'method' => 'delete', 'params' => 'id={id}');
$config->provider->actionList['delete']['className']    = 'ajax-submit';
$config->provider->actionList['delete']['data-confirm'] = array('message' => $lang->provider->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
