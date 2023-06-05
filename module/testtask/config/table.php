<?php
global $lang;
$config->testtask->dtable = new stdclass();
$config->testtask->dtable->fieldList['id']['name']  = 'id';
$config->testtask->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testtask->dtable->fieldList['id']['type']  = 'checkID';
$config->testtask->dtable->fieldList['id']['align'] = 'left';
$config->testtask->dtable->fieldList['id']['fixed'] = 'left';

$config->testtask->dtable->fieldList['title']['name']     = 'name';
$config->testtask->dtable->fieldList['title']['title']    = $lang->testtask->name;
$config->testtask->dtable->fieldList['title']['type']     = 'title';
$config->testtask->dtable->fieldList['title']['minWidth'] = '200';
$config->testtask->dtable->fieldList['title']['fixed']    = 'left';
$config->testtask->dtable->fieldList['title']['link']     = helper::createLink('testcase', 'view', "taskID={id}");

$config->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->testtask->dtable->fieldList['actions']['width']    = '180';
$config->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->testtask->dtable->fieldList['actions']['fixed']    = 'right';
