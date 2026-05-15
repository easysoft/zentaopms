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
$config->artifact->dtable->fieldList['name']['sortType'] = true;

$config->artifact->dtable->fieldList['size']['title']    = $lang->artifact->size;
$config->artifact->dtable->fieldList['size']['sortType'] = true;
$config->artifact->dtable->fieldList['size']['hint']     = true;

$config->artifact->dtable->fieldList['version']['title']    = $lang->artifact->version;
$config->artifact->dtable->fieldList['version']['sortType'] = false;
$config->artifact->dtable->fieldList['version']['hint']     = true;

$config->artifact->dtable->fieldList['arch']['title']    = $lang->artifact->arch;
$config->artifact->dtable->fieldList['arch']['sortType'] = false;
$config->artifact->dtable->fieldList['arch']['hint']     = true;

$config->artifact->dtable->fieldList['checkValue']['title']    = $lang->artifact->checkValue;
$config->artifact->dtable->fieldList['checkValue']['sortType'] = false;
$config->artifact->dtable->fieldList['checkValue']['hint']     = true;

$config->artifact->dtable->fieldList['creator']['name']     = 'creatorName';
$config->artifact->dtable->fieldList['creator']['title']    = $lang->artifact->creator;
$config->artifact->dtable->fieldList['creator']['sortType'] = false;
$config->artifact->dtable->fieldList['creator']['hint']     = true;
$config->artifact->dtable->fieldList['creator']['type']     = 'user';

$config->artifact->dtable->fieldList['createdDate']['name']  = 'created';
$config->artifact->dtable->fieldList['createdDate']['title'] = $lang->artifact->createdDate;
$config->artifact->dtable->fieldList['createdDate']['sortType'] = true;
$config->artifact->dtable->fieldList['createdDate']['type']  = 'datetime';
$config->artifact->dtable->fieldList['createdDate']['hint']  = true;

$config->artifact->dtable->fieldList['editor']['name']     = 'editorName';
$config->artifact->dtable->fieldList['editor']['title']    = $lang->artifact->editor;
$config->artifact->dtable->fieldList['editor']['sortType'] = false;
$config->artifact->dtable->fieldList['editor']['hint']     = true;
$config->artifact->dtable->fieldList['editor']['type']     = 'user';

$config->artifact->dtable->fieldList['updated']['name']     = 'updated';
$config->artifact->dtable->fieldList['updated']['title']    = $lang->artifact->editedDate;
$config->artifact->dtable->fieldList['updated']['sortType'] = true;
$config->artifact->dtable->fieldList['updated']['type']     = 'datetime';
$config->artifact->dtable->fieldList['updated']['hint']     = true;

$config->artifact->dtable->fieldList['actions']['name']  = 'actions';
$config->artifact->dtable->fieldList['actions']['title'] = $lang->actions;
$config->artifact->dtable->fieldList['actions']['type']  = 'actions';
$config->artifact->dtable->fieldList['actions']['width'] = '100';
$config->artifact->dtable->fieldList['actions']['menu']  = array('history', 'edit', 'download', 'move', 'delete');
$config->artifact->dtable->fieldList['actions']['list']  = $config->artifact->actionList;
