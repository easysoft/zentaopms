<?php
global $lang;
$config->testcase->dtable = new stdclass();
$config->testcase->dtable->fieldList['id']['name']  = 'id';
$config->testcase->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->dtable->fieldList['id']['type']  = 'checkID';
$config->testcase->dtable->fieldList['id']['align'] = 'left';
$config->testcase->dtable->fieldList['id']['fixed'] = 'left';

$config->testcase->dtable->fieldList['title']['name']     = 'title';
$config->testcase->dtable->fieldList['title']['title']    = $lang->testcase->title;
$config->testcase->dtable->fieldList['title']['type']     = 'title';
$config->testcase->dtable->fieldList['title']['minWidth'] = '200';
$config->testcase->dtable->fieldList['title']['fixed']    = 'left';
$config->testcase->dtable->fieldList['title']['link']     = helper::createLink('testcase', 'view', "caseID={id}");

$config->testcase->dtable->fieldList['actions']['name']     = 'actions';
$config->testcase->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testcase->dtable->fieldList['actions']['type']     = 'actions';
$config->testcase->dtable->fieldList['actions']['width']    = '180';
$config->testcase->dtable->fieldList['actions']['sortType'] = false;
$config->testcase->dtable->fieldList['actions']['fixed']    = 'right';
