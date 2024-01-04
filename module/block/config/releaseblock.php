<?php
global $lang, $app;
$app->loadLang('release');

$config->block->release = new stdclass();
$config->block->release->dtable = new stdclass();
$config->block->release->dtable->fieldList = array();
$config->block->release->dtable->fieldList['id']          = array('name' => 'id',          'title' => $lang->idAB,             'type' => 'id',     'sort' => 'number');
$config->block->release->dtable->fieldList['name']        = array('name' => 'name',        'title' => $lang->release->name,    'type' => 'title',  'sort' => true,  'flex'     => 1,     'link' => array('module' => 'release', 'method' => 'view', 'params' => 'release={id}'));
$config->block->release->dtable->fieldList['productName'] = array('name' => 'productName', 'title' => $lang->release->product, 'type' => 'text',   'sort' => true,  'minWidth' => '100', 'link' => array('module' => 'product', 'method' => 'browse', 'params' => 'productID={product}'));
$config->block->release->dtable->fieldList['buildName']   = array('name' => 'buildName',   'title' => $lang->release->build,   'type' => 'text',   'sort' => true,  'minWidth' => '100');
$config->block->release->dtable->fieldList['date']        = array('name' => 'date',        'title' => $lang->release->date,    'type' => 'date',   'sort' => 'date');
$config->block->release->dtable->fieldList['status']      = array('name' => 'status',      'title' => $lang->release->status,  'type' => 'status', 'sort' => true,  'statusMap' => $lang->release->statusList);
