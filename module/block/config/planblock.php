<?php
global $lang, $app;
$app->loadLang('productplan');

$config->block->plan = new stdclass();
$config->block->plan->dtable = new stdclass();
$config->block->plan->dtable->fieldList = array();
$config->block->plan->dtable->fieldList['id']      = array('name' => 'id',      'title' => $lang->idAB,                 'type' => 'id',     'sort' => 'number');
$config->block->plan->dtable->fieldList['title']   = array('name' => 'title',   'title' => $lang->productplan->title,   'type' => 'title',  'sort' => true,     'flex'     => 1,     'link' => array('module' => 'productplan', 'method' => 'view', 'params' => 'planID={id}'));
$config->block->plan->dtable->fieldList['product'] = array('name' => 'product', 'title' => $lang->productplan->product, 'type' => 'text',   'sort' => true,     'minWidth' => 100,   'link' => array('module' => 'product', 'method' => 'browse', 'params' => 'productID={product}'));
$config->block->plan->dtable->fieldList['hour']    = array('name' => 'hour',    'title' => $lang->productplan->hour,    'type' => 'text',   'sort' => 'number', 'align'    => 'right');
$config->block->plan->dtable->fieldList['bugs']    = array('name' => 'bugs',    'title' => $lang->productplan->bugs,    'type' => 'number', 'sort' => true);
$config->block->plan->dtable->fieldList['begin']   = array('name' => 'begin',   'title' => $lang->productplan->begin,   'type' => 'date',   'sort' => 'date');
$config->block->plan->dtable->fieldList['end']     = array('name' => 'end',     'title' => $lang->productplan->end,     'type' => 'date',   'sort' => 'date');
