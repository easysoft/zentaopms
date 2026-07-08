<?php
global $lang;
$config->artifact->dtable = new stdclass();
$config->artifact->dtable->fieldList['path']['name']     = 'path';
$config->artifact->dtable->fieldList['path']['title']    = $lang->artifact->path;
$config->artifact->dtable->fieldList['path']['hint']     = true;
$config->artifact->dtable->fieldList['path']['sortType'] = false;
$config->artifact->dtable->fieldList['path']['checkbox'] = true;

$config->artifact->dtable->fieldList['package']['name']     = 'package';
$config->artifact->dtable->fieldList['package']['title']    = $lang->artifact->package;
$config->artifact->dtable->fieldList['package']['hint']     = true;
$config->artifact->dtable->fieldList['package']['sortType'] = false;
$config->artifact->dtable->fieldList['package']['checkbox'] = true;

$config->artifact->dtable->fieldList['name']['name']     = 'name';
$config->artifact->dtable->fieldList['name']['title']    = $lang->artifact->name;
$config->artifact->dtable->fieldList['name']['hint']     = true;
$config->artifact->dtable->fieldList['name']['sortType'] = true;

$config->artifact->dtable->fieldList['size']['title']    = $lang->artifact->size;
$config->artifact->dtable->fieldList['size']['sortType'] = true;
$config->artifact->dtable->fieldList['size']['hint']     = true;

$config->artifact->dtable->fieldList['version']['title']    = $lang->artifact->version;
$config->artifact->dtable->fieldList['version']['sortType'] = false;
$config->artifact->dtable->fieldList['version']['hint']     = true;

$config->artifact->dtable->fieldList['sysArch']['title']    = $lang->artifact->arch;
$config->artifact->dtable->fieldList['sysArch']['sortType'] = false;
$config->artifact->dtable->fieldList['sysArch']['hint']     = true;

$config->artifact->dtable->fieldList['checkValue']['title']    = $lang->artifact->checkValue;
$config->artifact->dtable->fieldList['checkValue']['sortType'] = false;
$config->artifact->dtable->fieldList['checkValue']['hint']     = true;

$config->artifact->dtable->fieldList['editedBy']['name']     = 'editedBy';
$config->artifact->dtable->fieldList['editedBy']['title']    = $lang->artifact->editor;
$config->artifact->dtable->fieldList['editedBy']['sortType'] = false;
$config->artifact->dtable->fieldList['editedBy']['hint']     = true;
$config->artifact->dtable->fieldList['editedBy']['type']     = 'user';

$config->artifact->dtable->fieldList['editedDate']['name']       = 'editedDate';
$config->artifact->dtable->fieldList['editedDate']['title']      = $lang->artifact->editedDate;
$config->artifact->dtable->fieldList['editedDate']['sortType']   = true;
$config->artifact->dtable->fieldList['editedDate']['type']       = 'datetime';
$config->artifact->dtable->fieldList['editedDate']['hint']       = true;
$config->artifact->dtable->fieldList['editedDate']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->artifact->dtable->fieldList['actions']['name']  = 'actions';
$config->artifact->dtable->fieldList['actions']['title'] = $lang->actions;
$config->artifact->dtable->fieldList['actions']['type']  = 'actions';
$config->artifact->dtable->fieldList['actions']['width'] = '100';
$config->artifact->dtable->fieldList['actions']['menu']  = array('history', 'edit', 'download|copyCMD', 'move', 'delete');
$config->artifact->dtable->fieldList['actions']['list']  = $config->artifact->actionList;
