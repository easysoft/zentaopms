<?php
global $lang;
if(!isset($lang->errorlog)) $lang->errorlog = new stdclass();

$config->errorlog->actionList = array();
$config->errorlog->actionList['view']['icon']        = 'eye';
$config->errorlog->actionList['view']['text']        = '';
$config->errorlog->actionList['view']['hint']        = $lang->errorlog->view;
$config->errorlog->actionList['view']['url']         = array('module' => 'errorlog', 'method' => 'view', 'params' => 'id={id}');
$config->errorlog->actionList['view']['data-toggle'] = 'modal';
$config->errorlog->actionList['view']['data-size']   = 'lg';

$config->errorlog->actionList['delete']['icon']         = 'trash';
$config->errorlog->actionList['delete']['text']         = '';
$config->errorlog->actionList['delete']['hint']         = $lang->errorlog->delete;
$config->errorlog->actionList['delete']['url']          = array('module' => 'errorlog', 'method' => 'delete', 'params' => 'id={id}');
$config->errorlog->actionList['delete']['className']    = 'ajax-submit';
$config->errorlog->actionList['delete']['data-confirm'] = array('message' => $lang->errorlog->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->errorlog->dtable = new stdclass();
$config->errorlog->dtable->fieldList = array();
$config->errorlog->dtable->fieldList['id']['name']     = 'id';
$config->errorlog->dtable->fieldList['id']['title']    = $lang->idAB;
$config->errorlog->dtable->fieldList['id']['type']     = 'checkID';
$config->errorlog->dtable->fieldList['id']['checkbox'] = true;
$config->errorlog->dtable->fieldList['id']['fixed']    = 'left';
$config->errorlog->dtable->fieldList['id']['sortType'] = true;

$config->errorlog->dtable->fieldList['requestID']['name']     = 'requestID';
$config->errorlog->dtable->fieldList['requestID']['title']    = $lang->errorlog->requestID;
$config->errorlog->dtable->fieldList['requestID']['type']     = 'text';
$config->errorlog->dtable->fieldList['requestID']['sortType'] = true;
$config->errorlog->dtable->fieldList['requestID']['width']    = '190';
$config->errorlog->dtable->fieldList['requestID']['flex']     = false;

$config->errorlog->dtable->fieldList['module']['name']     = 'module';
$config->errorlog->dtable->fieldList['module']['title']    = $lang->errorlog->module;
$config->errorlog->dtable->fieldList['module']['type']     = 'text';
$config->errorlog->dtable->fieldList['module']['sortType'] = true;
$config->errorlog->dtable->fieldList['module']['width']    = '90';
$config->errorlog->dtable->fieldList['module']['flex']     = false;

$config->errorlog->dtable->fieldList['method']['name']     = 'method';
$config->errorlog->dtable->fieldList['method']['title']    = $lang->errorlog->method;
$config->errorlog->dtable->fieldList['method']['type']     = 'text';
$config->errorlog->dtable->fieldList['method']['sortType'] = true;
$config->errorlog->dtable->fieldList['method']['width']    = '130';
$config->errorlog->dtable->fieldList['method']['flex']     = false;

$config->errorlog->dtable->fieldList['level']['name']     = 'level';
$config->errorlog->dtable->fieldList['level']['title']    = $lang->errorlog->level;
$config->errorlog->dtable->fieldList['level']['type']     = 'text';
$config->errorlog->dtable->fieldList['level']['sortType'] = true;
$config->errorlog->dtable->fieldList['level']['width']    = '110';
$config->errorlog->dtable->fieldList['level']['flex']     = false;

$config->errorlog->dtable->fieldList['account']['name']     = 'account';
$config->errorlog->dtable->fieldList['account']['title']    = $lang->errorlog->account;
$config->errorlog->dtable->fieldList['account']['type']     = 'text';
$config->errorlog->dtable->fieldList['account']['sortType'] = true;
$config->errorlog->dtable->fieldList['account']['width']    = '90';
$config->errorlog->dtable->fieldList['account']['flex']     = false;

$config->errorlog->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->errorlog->dtable->fieldList['createdDate']['title']    = $lang->errorlog->createdDate;
$config->errorlog->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->errorlog->dtable->fieldList['createdDate']['sortType'] = true;
$config->errorlog->dtable->fieldList['createdDate']['width']    = '128';

$config->errorlog->dtable->fieldList['message']['name']     = 'message';
$config->errorlog->dtable->fieldList['message']['title']    = $lang->errorlog->message;
$config->errorlog->dtable->fieldList['message']['type']     = 'text';
$config->errorlog->dtable->fieldList['message']['flex']     = true;
$config->errorlog->dtable->fieldList['message']['minWidth'] = '200';
$config->errorlog->dtable->fieldList['message']['cellStyle'] = array('whiteSpace' => 'nowrap', 'overflow' => 'hidden', 'textOverflow' => 'ellipsis');

$config->errorlog->dtable->fieldList['actions']['title'] = $lang->actions;
$config->errorlog->dtable->fieldList['actions']['type']  = 'actions';
$config->errorlog->dtable->fieldList['actions']['list']  = $config->errorlog->actionList;
$config->errorlog->dtable->fieldList['actions']['menu']  = array_keys($config->errorlog->actionList);
