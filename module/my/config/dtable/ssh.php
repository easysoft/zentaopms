<?php
$config->my->ssh = new stdclass();
$config->my->ssh->actionList = array();
$config->my->ssh->actionList['edit']['icon']        = 'edit';
$config->my->ssh->actionList['edit']['text']        = $lang->edit;
$config->my->ssh->actionList['edit']['hint']        = $lang->edit;
$config->my->ssh->actionList['edit']['url']         = array('module' => 'my', 'method' => 'editSSH', 'params' => "sshID={id}");
$config->my->ssh->actionList['edit']['data-toggle'] = 'modal';

$config->my->ssh->actionList['delete']['icon']         = 'trash';
$config->my->ssh->actionList['delete']['text']         = $lang->delete;
$config->my->ssh->actionList['delete']['hint']         = $lang->delete;
$config->my->ssh->actionList['delete']['url']          = array('module' => 'my', 'method' => 'deleteSSH', 'params' => "sshID={id}");
$config->my->ssh->actionList['delete']['className']    = 'ajax-submit';
$config->my->ssh->actionList['delete']['data-confirm'] = array('message' => $lang->my->confirmDeleteSSH);

$config->my->ssh->dtable = new stdclass();
$config->my->ssh->dtable->fieldList['name']['name']     = 'identifier';
$config->my->ssh->dtable->fieldList['name']['title']    = $lang->my->name;
$config->my->ssh->dtable->fieldList['name']['type']     = 'title';
$config->my->ssh->dtable->fieldList['name']['sortType'] = false;

$config->my->ssh->dtable->fieldList['createdDate']['name']       = 'createdDate';
$config->my->ssh->dtable->fieldList['createdDate']['title']      = $lang->my->createdDate;
$config->my->ssh->dtable->fieldList['createdDate']['type']       = 'datetime';
$config->my->ssh->dtable->fieldList['createdDate']['formatDate'] = 'yyyy-MM-dd hh:mm:ss';
$config->my->ssh->dtable->fieldList['createdDate']['sortType']   = false;

$config->my->ssh->dtable->fieldList['lastUsed']['name']     = 'lastUsedDate';
$config->my->ssh->dtable->fieldList['lastUsed']['title']    = $lang->my->lastUsed;
$config->my->ssh->dtable->fieldList['lastUsed']['sortType'] = false;

$config->my->ssh->dtable->fieldList['actions']['name']     = 'actions';
$config->my->ssh->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->ssh->dtable->fieldList['actions']['type']     = 'actions';
$config->my->ssh->dtable->fieldList['actions']['sortType'] = false;
$config->my->ssh->dtable->fieldList['actions']['list']     = $config->my->ssh->actionList;
$config->my->ssh->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
