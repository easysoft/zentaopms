<?php
global $lang, $app;
$app->loadLang('release');

if(!isset($config->block->dtable)) $config->block->dtable = new stdclass();
$config->block->dtable->release = new stdclass();
$config->block->dtable->release->fieldList['id']['name']     = 'id';
$config->block->dtable->release->fieldList['id']['title']    = $lang->idAB;
$config->block->dtable->release->fieldList['id']['type']     = 'id';
$config->block->dtable->release->fieldList['id']['sortType'] = true;

$config->block->dtable->release->fieldList['name']['name']     = 'name';
$config->block->dtable->release->fieldList['name']['title']    = $lang->release->name;
$config->block->dtable->release->fieldList['name']['type']     = 'title';
$config->block->dtable->release->fieldList['name']['flex']     = 1;
$config->block->dtable->release->fieldList['name']['sortType'] = true;

$config->block->dtable->release->fieldList['productName']['name']     = 'productName';
$config->block->dtable->release->fieldList['productName']['title']    = $lang->release->product;
$config->block->dtable->release->fieldList['productName']['type']     = 'text';
$config->block->dtable->release->fieldList['productName']['minWidth'] = '100';
$config->block->dtable->release->fieldList['productName']['sortType'] = true;

$config->block->dtable->release->fieldList['buildName']['name']     = 'buildName';
$config->block->dtable->release->fieldList['buildName']['title']    = $lang->release->build;
$config->block->dtable->release->fieldList['buildName']['type']     = 'text';
$config->block->dtable->release->fieldList['buildName']['minWidth'] = '100';
$config->block->dtable->release->fieldList['buildName']['sortType'] = true;

$config->block->dtable->release->fieldList['date']['name']     = 'date';
$config->block->dtable->release->fieldList['date']['title']    = $lang->release->date;
$config->block->dtable->release->fieldList['date']['type']     = 'date';
$config->block->dtable->release->fieldList['date']['sortType'] = true;

$config->block->dtable->release->fieldList['status']['name']     = 'status';
$config->block->dtable->release->fieldList['status']['title']    = $lang->release->status;
$config->block->dtable->release->fieldList['status']['type']     = 'status';
$config->block->dtable->release->fieldList['status']['sortType'] = true;
