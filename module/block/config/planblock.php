<?php
global $lang, $app;
$app->loadLang('productplan');

$config->block->plan = new stdclass();
$config->block->plan->dtable = array();
$config->block->plan->dtable['id']      = array('name' => 'id',      'title' => $lang->idAB,                 'type' => 'id',     'sortType' => 1);
$config->block->plan->dtable['title']   = array('name' => 'title',   'title' => $lang->productplan->title,   'type' => 'title',  'sortType' => 1, 'flex'     => 1,   'type' => 'link');
$config->block->plan->dtable['product'] = array('name' => 'product', 'title' => $lang->productplan->product, 'type' => 'text',   'sortType' => 1, 'minWidth' => 100, 'type' => 'link');
$config->block->plan->dtable['hour']    = array('name' => 'hour',    'title' => $lang->productplan->hour,    'type' => 'count',  'sortType' => 1);
$config->block->plan->dtable['bugs']    = array('name' => 'bugs',    'title' => $lang->productplan->bugs,    'type' => 'number', 'sortType' => 1);
$config->block->plan->dtable['begin']   = array('name' => 'begin',   'title' => $lang->productplan->begin,   'type' => 'date',   'sortType' => 1);
$config->block->plan->dtable['end']     = array('name' => 'end',     'title' => $lang->productplan->end,     'type' => 'date',   'sortType' => 1);
