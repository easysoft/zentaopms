<?php
global $lang, $app;
$app->loadLang('product');

$config->block->product = new stdclass();
$config->block->product->dtable = new stdclass();
$config->block->product->dtable->fieldList = array();
$config->block->product->dtable->fieldList['name']              = array('name' => 'name',              'title' => $lang->product->name,                         'type' => 'title',     'sortType' => 1, 'flex' => 1);
$config->block->product->dtable->fieldList['manager']           = array('name' => 'manager',           'title' => $lang->product->manager,                      'type' => 'avatarBtn', 'sortType' => 1);
$config->block->product->dtable->fieldList['unclosedFeedback']  = array('name' => 'unclosedFeedback',  'title' => $lang->block->productlist->unclosedFeedback,  'type' => 'number',    'sortType' => 1);
$config->block->product->dtable->fieldList['activatedStory']    = array('name' => 'activatedStory',    'title' => $lang->block->productlist->activatedStory,    'type' => 'number',    'sortType' => 1);
$config->block->product->dtable->fieldList['storyCompleteRate'] = array('name' => 'storyCompleteRate', 'title' => $lang->block->productlist->storyCompleteRate, 'type' => 'number',    'sortType' => 1);
$config->block->product->dtable->fieldList['plan']              = array('name' => 'plan',              'title' => $lang->product->plan,                         'type' => 'number',    'sortType' => 1);
$config->block->product->dtable->fieldList['activatedBug']      = array('name' => 'activatedBug',      'title' => $lang->block->productlist->activatedBug,      'type' => 'number',    'sortType' => 1);
$config->block->product->dtable->fieldList['release']           = array('name' => 'release',           'title' => $lang->product->release,                      'type' => 'number',    'sortType' => 1);
