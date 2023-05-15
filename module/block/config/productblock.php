<?php
global $lang, $app;
$app->loadLang('product');

$config->block->product = new stdclass();
$config->block->product->dtable = new stdclass();
$config->block->product->dtable->fieldList = array();
$config->block->product->dtable->fieldList['name']['name']     = 'name';
$config->block->product->dtable->fieldList['name']['title']    = $lang->product->name;
$config->block->product->dtable->fieldList['name']['type']     = 'title';
$config->block->product->dtable->fieldList['name']['sortType'] = 1;
$config->block->product->dtable->fieldList['name']['width']    = 250;
$config->block->product->dtable->fieldList['name']['type']     = 'link';

$config->block->product->dtable->fieldList['manager']['name']     = 'product';
$config->block->product->dtable->fieldList['manager']['title']    = $lang->product->manager;
$config->block->product->dtable->fieldList['manager']['type']     = 'avatarBtn';
$config->block->product->dtable->fieldList['manager']['sortType'] = 1;
$config->block->product->dtable->fieldList['manager']['minWidth'] = 100;

$config->block->product->dtable->fieldList['unclosedFeedback']['name']     = 'unclosedFeedback';
$config->block->product->dtable->fieldList['unclosedFeedback']['title']    = $lang->block->productlist->unclosedFeedback;
$config->block->product->dtable->fieldList['unclosedFeedback']['type']     = 'count';
$config->block->product->dtable->fieldList['unclosedFeedback']['sortType'] = 1;

$config->block->product->dtable->fieldList['activatedStory']['name']     = 'activatedStory';
$config->block->product->dtable->fieldList['activatedStory']['title']    = $lang->block->productlist->activatedStory;
$config->block->product->dtable->fieldList['activatedStory']['type']     = 'number';
$config->block->product->dtable->fieldList['activatedStory']['sortType'] = 1;

$config->block->product->dtable->fieldList['storyCompleteRate']['name']     = 'storyCompleteRate';
$config->block->product->dtable->fieldList['storyCompleteRate']['title']    = $lang->block->productlist->storyCompleteRate;
$config->block->product->dtable->fieldList['storyCompleteRate']['type']     = 'date';
$config->block->product->dtable->fieldList['storyCompleteRate']['sortType'] = 1;

$config->block->product->dtable->fieldList['plan']['name']     = 'plan';
$config->block->product->dtable->fieldList['plan']['title']    = $lang->product->plan;
$config->block->product->dtable->fieldList['plan']['type']     = 'date';
$config->block->product->dtable->fieldList['plan']['sortType'] = 1;

$config->block->product->dtable->fieldList['activatedBug']['name']     = 'activatedBug';
$config->block->product->dtable->fieldList['activatedBug']['title']    = $lang->block->productlist->activatedBug;
$config->block->product->dtable->fieldList['activatedBug']['type']     = 'date';
$config->block->product->dtable->fieldList['activatedBug']['sortType'] = 1;

$config->block->product->dtable->fieldList['release']['name']      = 'release';
$config->block->product->dtable->fieldList['release']['title']     = $lang->product->release;
$config->block->product->dtable->fieldList['release']['type']      = 'date';
$config->block->product->dtable->fieldList['release']['sortType']  = 1;
