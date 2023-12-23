<?php
global $lang, $app;
$app->loadLang('product');

$config->block->product = new stdclass();
$config->block->product->dtable = new stdclass();
$config->block->product->dtable->fieldList = array();
$config->block->product->dtable->fieldList['name']              = array('name' => 'name',              'title' => $lang->product->name,                         'type' => 'title',     'sort' => true, 'flex' => 1, 'link' => array('module' => 'product', 'method' => 'browse', 'params' => 'productID={id}'));
$config->block->product->dtable->fieldList['PO']                = array('name' => 'PO',                'title' => $lang->product->manager,                      'type' => 'avatarBtn', 'sort' => true);
if($config->edition != 'open') $config->block->product->dtable->fieldList['unclosedFeedback'] = array('name' => 'unclosedFeedback',  'title' => $lang->block->productlist->unclosedFeedback,  'type' => 'number',    'sort' => 'number');
$config->block->product->dtable->fieldList['activeStories']     = array('name' => 'activeStories',     'title' => $lang->block->productlist->activatedStory,    'type' => 'number',    'sort' => 'number');
$config->block->product->dtable->fieldList['progress']          = array('name' => 'progress',          'title' => $lang->block->productlist->storyCompleteRate, 'type' => 'number',    'sort' => 'number');
$config->block->product->dtable->fieldList['plans']             = array('name' => 'plans',             'title' => $lang->product->plan,                         'type' => 'number',    'sort' => 'number');
$config->block->product->dtable->fieldList['unresolvedBugs']    = array('name' => 'unresolvedBugs',    'title' => $lang->block->productlist->activatedBug,      'type' => 'number',    'sort' => 'number');
$config->block->product->dtable->fieldList['releases']          = array('name' => 'releases',          'title' => $lang->product->release,                      'type' => 'number',    'sort' => 'number');
