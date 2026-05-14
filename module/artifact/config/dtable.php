<?php
global $lang;
$config->artifact->dtable = new stdclass();
$config->artifact->dtable->fieldList['path']['name']     = 'path';
$config->artifact->dtable->fieldList['path']['title']    = $lang->artifact->path;
$config->artifact->dtable->fieldList['path']['hint']     = true;
$config->artifact->dtable->fieldList['path']['sortType'] = false;

$config->artifact->dtable->fieldList['name']['name']     = 'name';
$config->artifact->dtable->fieldList['name']['title']    = $lang->artifact->name;
$config->artifact->dtable->fieldList['name']['hint']     = true;
$config->artifact->dtable->fieldList['name']['sortType'] = false;

$config->artifact->dtable->fieldList['size']['title']    = $lang->artifact->size;
$config->artifact->dtable->fieldList['size']['sortType'] = false;
$config->artifact->dtable->fieldList['size']['hint']     = true;

$config->artifact->dtable->fieldList['checkValue']['title']    = $lang->artifact->checkValue;
$config->artifact->dtable->fieldList['checkValue']['sortType'] = false;
$config->artifact->dtable->fieldList['checkValue']['hint']     = true;

$config->artifact->dtable->fieldList['creator']['name']     = 'creator';
$config->artifact->dtable->fieldList['creator']['title']    = $lang->artifact->creator;
$config->artifact->dtable->fieldList['creator']['sortType'] = false;
$config->artifact->dtable->fieldList['creator']['width']    = '136';
$config->artifact->dtable->fieldList['creator']['hint']     = true;
$config->artifact->dtable->fieldList['creator']['type']     = 'user';

$config->artifact->dtable->fieldList['createdDate']['name']  = 'created';
$config->artifact->dtable->fieldList['createdDate']['title'] = $lang->artifact->createdDate;
$config->artifact->dtable->fieldList['createdDate']['type']  = 'datetime';
$config->artifact->dtable->fieldList['createdDate']['hint']  = true;

$config->artifact->dtable->fieldList['actions']['name']  = 'actions';
$config->artifact->dtable->fieldList['actions']['title'] = $lang->actions;
$config->artifact->dtable->fieldList['actions']['type']  = 'actions';
$config->artifact->dtable->fieldList['actions']['width'] = '100';
$config->artifact->dtable->fieldList['actions']['menu']  = array('history', 'edit', 'download', 'move', 'delete');
$config->artifact->dtable->fieldList['actions']['list']  = $config->artifact->actionList;
