<?php
global $app, $lang;
$app->loadLang('build');

$config->block->build = new stdclass();
$config->block->build->dtable = new stdclass();
$config->block->build->dtable->fieldList = array();
$config->block->build->dtable->fieldList['id']['name']  = 'id';
$config->block->build->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->build->dtable->fieldList['id']['type']  = 'id';
$config->block->build->dtable->fieldList['id']['fixed'] = false;
$config->block->build->dtable->fieldList['id']['sort']  = 'number';

$config->block->build->dtable->fieldList['name']['name']  = 'name';
$config->block->build->dtable->fieldList['name']['title'] = $lang->build->name;
$config->block->build->dtable->fieldList['name']['link']  = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={id}');
$config->block->build->dtable->fieldList['name']['type']  = 'title';
$config->block->build->dtable->fieldList['name']['fixed'] = false;
$config->block->build->dtable->fieldList['name']['sort']  = true;

$config->block->build->dtable->fieldList['product']['name']  = 'productName';
$config->block->build->dtable->fieldList['product']['title'] = $lang->build->product;
$config->block->build->dtable->fieldList['product']['type']  = 'text';
$config->block->build->dtable->fieldList['product']['sort']  = true;

$config->block->build->dtable->fieldList['project']['name']  = 'projectName';
$config->block->build->dtable->fieldList['project']['title'] = $lang->build->project;
$config->block->build->dtable->fieldList['project']['type']  = 'text';
$config->block->build->dtable->fieldList['project']['sort']  = true;

$config->block->build->dtable->fieldList['date']['name']  = 'date';
$config->block->build->dtable->fieldList['date']['title'] = $lang->build->date;
$config->block->build->dtable->fieldList['date']['type']  = 'date';
$config->block->build->dtable->fieldList['date']['sort']  = 'date';
