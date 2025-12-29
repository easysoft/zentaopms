<?php
global $lang;
$config->reporeviewflow->editor = new stdclass();
$config->reporeviewflow->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->reporeviewflow->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->reporeviewflow->actionList = array();

$config->reporeviewflow->actionList['edit']['icon'] = 'edit';
$config->reporeviewflow->actionList['edit']['hint'] = $lang->reporeviewflow->edit;
$config->reporeviewflow->actionList['edit']['url']  = array('module' => 'reporeviewflow', 'method' => 'edit', 'params' => "repoID={repo}&flowID={id}");

$config->reporeviewflow->actionList['enable']['icon']         = 'active';
$config->reporeviewflow->actionList['enable']['hint']         = $lang->reporeviewflow->enableFlow;
$config->reporeviewflow->actionList['enable']['url']          = array('module' => 'reporeviewflow', 'method' => 'changeStatus', 'params' => "flowID={id}&status=enable");
$config->reporeviewflow->actionList['enable']['className']    = 'ajax-submit';
$config->reporeviewflow->actionList['enable']['notLoadModel'] = true;

$config->reporeviewflow->actionList['disable']['icon']         = 'cancel';
$config->reporeviewflow->actionList['disable']['hint']         = $lang->reporeviewflow->disableFlow;
$config->reporeviewflow->actionList['disable']['url']          = array('module' => 'reporeviewflow', 'method' => 'changeStatus', 'params' => "flowID={id}&status=disable");
$config->reporeviewflow->actionList['disable']['className']    = 'ajax-submit';
$config->reporeviewflow->actionList['disable']['notLoadModel'] = true;

$config->reporeviewflow->actionList['delete']['icon']         = 'trash';
$config->reporeviewflow->actionList['delete']['hint']         = $lang->reporeviewflow->delete;
$config->reporeviewflow->actionList['delete']['data-confirm'] = array('message' => $lang->reporeviewflow->notice->deleteReviewFlow, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->reporeviewflow->actionList['delete']['className']    = 'ajax-submit';
$config->reporeviewflow->actionList['delete']['url']          = array('module' => 'reporeviewflow', 'method' => 'delete', 'params' => "flowID={id}");
