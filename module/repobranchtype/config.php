<?php
global $lang;

$config->repobranchtype = new stdclass();
$config->repobranchtype->actionList = array();

$config->repobranchtype->actionList['rule']['icon']        = 'data-structure';
$config->repobranchtype->actionList['rule']['hint']        = $lang->repobranchtype->setBranchRule;
$config->repobranchtype->actionList['rule']['url']         = array('module' => 'repobranchrule', 'method' => 'setBranchRule', 'params' => 'branchTypeID={id}&repoID={repoID}&branchName=');
$config->repobranchtype->actionList['rule']['data-toggle'] = 'modal';

$config->repobranchtype->actionList['edit']['icon']        = 'edit';
$config->repobranchtype->actionList['edit']['hint']        = $lang->repobranchtype->edit;
$config->repobranchtype->actionList['edit']['url']         = array('module' => 'repobranchtype', 'method' => 'edit', 'params' => 'repoID={repoID}&typeID={id}');
$config->repobranchtype->actionList['edit']['data-toggle'] = 'modal';

$config->repobranchtype->actionList['delete']['icon']         = 'trash';
$config->repobranchtype->actionList['delete']['hint']         = $lang->repobranchtype->delete;
$config->repobranchtype->actionList['delete']['url']          = array('module' => 'repobranchtype', 'method' => 'delete', 'params' => 'repoID={repoID}&typeID={id}');
$config->repobranchtype->actionList['delete']['data-confirm'] = array('message' => $lang->repobranchtype->notice->delete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->repobranchtype->actionList['delete']['className']    = 'ajax-submit';
