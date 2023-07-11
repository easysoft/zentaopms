<?php
global $lang, $app;
$app->loadLang('my');
$app->loadModuleConfig('my');

$config->block->review = new stdclass();
$config->block->review->dtable = new stdclass();
$config->block->review->dtable->fieldList = array();
$config->block->review->dtable->fieldList = $config->my->audit->dtable->fieldList;
unset($config->block->review->dtable->fieldList['result']);
unset($config->block->review->dtable->fieldList['actions']);

$config->block->review->dtable->short = new stdclass();
$config->block->review->dtable->short->fieldList['id']     = $config->block->review->dtable->fieldList['id'];
$config->block->review->dtable->short->fieldList['title']  = $config->block->review->dtable->fieldList['title'];
$config->block->review->dtable->short->fieldList['status'] = $config->block->review->dtable->fieldList['status'];
