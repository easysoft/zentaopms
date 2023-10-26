<?php
global $lang, $app;
$app->loadLang('doc');

$config->block->doc = new stdclass();
$config->block->doc->dtable = new stdclass();
$config->block->doc->dtable->fieldList = array();
$config->block->doc->dtable->fieldList['title']['name']  = 'title';
$config->block->doc->dtable->fieldList['title']['title'] = $lang->doc->title;
$config->block->doc->dtable->fieldList['title']['link']  = array('module' => 'doc', 'method' => 'view', 'params' => 'docID={id}');
$config->block->doc->dtable->fieldList['title']['type']  = 'title';
$config->block->doc->dtable->fieldList['title']['sort']  = true;

$config->block->doc->dtable->fieldList['addedBy']['name']  = 'addedBy';
$config->block->doc->dtable->fieldList['addedBy']['title'] = $lang->doc->addedBy;
$config->block->doc->dtable->fieldList['addedBy']['type']  = 'user';
$config->block->doc->dtable->fieldList['addedBy']['sort']  = true;

$config->block->doc->dtable->fieldList['addedDate']['name']  = 'addedDate';
$config->block->doc->dtable->fieldList['addedDate']['title'] = $lang->doc->addedDate;
$config->block->doc->dtable->fieldList['addedDate']['type']  = 'date';
$config->block->doc->dtable->fieldList['addedDate']['sort']  = 'date';

$config->block->doc->dtable->fieldList['editedDate']['name']  = 'editedDate';
$config->block->doc->dtable->fieldList['editedDate']['title'] = $lang->doc->editedDate;
$config->block->doc->dtable->fieldList['editedDate']['type']  = 'date';
$config->block->doc->dtable->fieldList['editedDate']['sort']  = 'date';
