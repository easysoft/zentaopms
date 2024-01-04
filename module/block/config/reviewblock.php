<?php
global $lang, $app;
$app->loadLang('my');
$app->loadModuleConfig('my');

$config->block->review = new stdclass();
$config->block->review->dtable = new stdclass();
$config->block->review->dtable->fieldList = array();
$config->block->review->dtable->fieldList = $config->my->audit->dtable->fieldList;

$config->block->review->dtable->fieldList['id']['sort']     = 'number';
$config->block->review->dtable->fieldList['title']['sort']  = true;
$config->block->review->dtable->fieldList['type']['sort']   = true;
$config->block->review->dtable->fieldList['time']['sort']   = 'date';
$config->block->review->dtable->fieldList['result']['sort'] = true;
$config->block->review->dtable->fieldList['status']['sort'] = true;

unset($config->block->review->dtable->fieldList['result']);
unset($config->block->review->dtable->fieldList['actions']);
unset($config->block->review->dtable->fieldList['id']['sortType']);
unset($config->block->review->dtable->fieldList['title']['sortType']);
unset($config->block->review->dtable->fieldList['type']['sortType']);
unset($config->block->review->dtable->fieldList['time']['sortType']);
unset($config->block->review->dtable->fieldList['result']['sortType']);
unset($config->block->review->dtable->fieldList['status']['sortType']);

$config->block->review->dtable->short = new stdclass();
$config->block->review->dtable->short->fieldList['id']     = $config->block->review->dtable->fieldList['id'];
$config->block->review->dtable->short->fieldList['title']  = $config->block->review->dtable->fieldList['title'];
$config->block->review->dtable->short->fieldList['status'] = $config->block->review->dtable->fieldList['status'];
