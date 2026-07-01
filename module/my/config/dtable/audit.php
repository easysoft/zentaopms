<?php
$config->my->audit = new stdclass();
$config->my->audit->actionList = array();
$config->my->audit->actionList['review']['icon']        = 'glasses';
$config->my->audit->actionList['review']['text']        = $lang->review->common;
$config->my->audit->actionList['review']['hint']        = $lang->review->common;
$config->my->audit->actionList['review']['url']         = array('module' => 'index', 'method' => 'index');
$config->my->audit->actionList['review']['data-toggle'] = 'modal';

$config->my->audit->dtable = new stdclass();
$config->my->audit->dtable->fieldList['id']['name']     = 'id';
$config->my->audit->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->audit->dtable->fieldList['id']['type']     = 'id';
$config->my->audit->dtable->fieldList['id']['sortType'] = true;
$config->my->audit->dtable->fieldList['id']['fixed']    = 'left';
$config->my->audit->dtable->fieldList['id']['required'] = true;

$config->my->audit->dtable->fieldList['title']['name']        = 'title';
$config->my->audit->dtable->fieldList['title']['title']       = $lang->my->auditField->title;
$config->my->audit->dtable->fieldList['title']['type']        = 'title';
$config->my->audit->dtable->fieldList['title']['fixed']       = 'left';
$config->my->audit->dtable->fieldList['title']['link']        = array('module' => '{module}', 'method' => 'view', 'params' => 'id={id}');
$config->my->audit->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->my->audit->dtable->fieldList['title']['data-size']   = 'lg';
$config->my->audit->dtable->fieldList['title']['sortType']    = true;
$config->my->audit->dtable->fieldList['title']['required']    = true;

$config->my->audit->dtable->fieldList['status']['name']     = 'status';
$config->my->audit->dtable->fieldList['status']['title']    = $lang->my->auditField->status;
$config->my->audit->dtable->fieldList['status']['type']     = 'status';
$config->my->audit->dtable->fieldList['status']['sortType'] = true;
$config->my->audit->dtable->fieldList['status']['show']     = true;

$config->my->audit->dtable->fieldList['type']['name']     = 'type';
$config->my->audit->dtable->fieldList['type']['title']    = $lang->my->auditField->type;
$config->my->audit->dtable->fieldList['type']['type']     = 'text';
$config->my->audit->dtable->fieldList['type']['sortType'] = true;
$config->my->audit->dtable->fieldList['type']['width']    = 100;
$config->my->audit->dtable->fieldList['type']['show']     = true;

$config->my->audit->dtable->fieldList['project']['name']     = 'project';
$config->my->audit->dtable->fieldList['project']['title']    = $lang->my->auditField->project;
$config->my->audit->dtable->fieldList['project']['type']     = 'text';
$config->my->audit->dtable->fieldList['project']['sortType'] = true;
$config->my->audit->dtable->fieldList['project']['width']    = 160;
$config->my->audit->dtable->fieldList['project']['show']     = true;

$config->my->audit->dtable->fieldList['product']['name']     = 'product';
$config->my->audit->dtable->fieldList['product']['title']    = $lang->my->auditField->product;
$config->my->audit->dtable->fieldList['product']['type']     = 'text';
$config->my->audit->dtable->fieldList['product']['sortType'] = true;
$config->my->audit->dtable->fieldList['product']['show']     = true;

$config->my->audit->dtable->fieldList['reviewer']['name']     = 'reviewer';
$config->my->audit->dtable->fieldList['reviewer']['title']    = $lang->my->auditField->reviewer;
$config->my->audit->dtable->fieldList['reviewer']['type']     = 'user';
$config->my->audit->dtable->fieldList['reviewer']['sortType'] = true;
$config->my->audit->dtable->fieldList['reviewer']['show']     = false;

$config->my->audit->dtable->fieldList['opinion']['name']     = 'opinion';
$config->my->audit->dtable->fieldList['opinion']['title']    = $lang->my->auditField->opinion;
$config->my->audit->dtable->fieldList['opinion']['type']     = 'text';
$config->my->audit->dtable->fieldList['opinion']['sortType'] = false;
$config->my->audit->dtable->fieldList['opinion']['show']     = false;

$config->my->audit->dtable->fieldList['result']['name']     = 'result';
$config->my->audit->dtable->fieldList['result']['title']    = $lang->my->auditField->result;
$config->my->audit->dtable->fieldList['result']['type']     = 'text';
$config->my->audit->dtable->fieldList['result']['sortType'] = true;
$config->my->audit->dtable->fieldList['result']['show']     = true;

$config->my->audit->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->audit->dtable->fieldList['openedBy']['title']    = $lang->my->auditField->openedBy;
$config->my->audit->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->audit->dtable->fieldList['openedBy']['sortType'] = true;
$config->my->audit->dtable->fieldList['openedBy']['show']     = false;

$config->my->audit->dtable->fieldList['time']['name']     = 'time';
$config->my->audit->dtable->fieldList['time']['title']    = $lang->my->auditField->time;
$config->my->audit->dtable->fieldList['time']['type']     = 'datetime';
$config->my->audit->dtable->fieldList['time']['sortType'] = true;
$config->my->audit->dtable->fieldList['time']['show']     = true;

$config->my->audit->dtable->fieldList['reviewTime']['name']     = 'reviewTime';
$config->my->audit->dtable->fieldList['reviewTime']['title']    = $lang->my->auditField->reviewTime;
$config->my->audit->dtable->fieldList['reviewTime']['type']     = 'datetime';
$config->my->audit->dtable->fieldList['reviewTime']['sortType'] = true;
$config->my->audit->dtable->fieldList['reviewTime']['show']     = false;

$config->my->audit->dtable->fieldList['actions']['name']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->audit->dtable->fieldList['actions']['type']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['sortType'] = false;
$config->my->audit->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->audit->dtable->fieldList['actions']['required'] = true;
$config->my->audit->dtable->fieldList['actions']['width']    = 64;
$config->my->audit->dtable->fieldList['actions']['list']     = $config->my->audit->actionList;
$config->my->audit->dtable->fieldList['actions']['menu']     = array('review');
