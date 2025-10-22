<?php
global $lang;
$config->devopsspace->actionList = array();
$config->devopsspace->actionList['repo']['icon']     = 'code';
$config->devopsspace->actionList['repo']['text']     = $lang->devopsspace->repo;
$config->devopsspace->actionList['repo']['hint']     = $lang->devopsspace->repo;
$config->devopsspace->actionList['repo']['showText'] = true;
$config->devopsspace->actionList['repo']['url']      = array('module' => 'repo', 'method' => 'maintain', 'params' => 'space={id}');

$config->devopsspace->actionList['artifactrepo']['icon']     = 'stack';
$config->devopsspace->actionList['artifactrepo']['text']     = $lang->devopsspace->artifactrepo;
$config->devopsspace->actionList['artifactrepo']['hint']     = $lang->devopsspace->artifactrepo;
$config->devopsspace->actionList['artifactrepo']['showText'] = true;
$config->devopsspace->actionList['artifactrepo']['url']      = array('module' => 'artifactrepo', 'method' => 'browse', 'params' => 'id={id}');

$config->devopsspace->actionList['edit']['icon']        = 'edit';
$config->devopsspace->actionList['edit']['text']        = $lang->edit;
$config->devopsspace->actionList['edit']['hint']        = $lang->edit;
$config->devopsspace->actionList['edit']['showText']    = true;
$config->devopsspace->actionList['edit']['url']         = array('module' => 'devopsspace', 'method' => 'edit', 'params' => 'id={id}');
$config->devopsspace->actionList['edit']['data-toggle'] = 'modal';

$config->devopsspace->actionList['delete']['icon']         = 'trash';
$config->devopsspace->actionList['delete']['text']         = $lang->delete;
$config->devopsspace->actionList['delete']['hint']         = $lang->delete;
$config->devopsspace->actionList['delete']['showText']     = true;
$config->devopsspace->actionList['delete']['ajaxSubmit']   = true;
$config->devopsspace->actionList['delete']['data-confirm'] = array('message' => $lang->devopsspace->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->devopsspace->actionList['delete']['url']          = array('module' => 'devopsspace', 'method' => 'delete', 'params' => 'id={id}');
