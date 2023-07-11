<?php
global $lang, $app;
$app->loadLang('testtask');

$config->block->testtask = new stdclass();
$config->block->testtask->dtable = new stdclass();
$config->block->testtask->dtable->fieldList = array();
$config->block->testtask->dtable->fieldList['id']      = array('name' => 'id',             'title' => $lang->idAB,                                           'type' => 'id' ,     'sortType' => true);
$config->block->testtask->dtable->fieldList['name']    = array('name' => 'name',           'title' => $lang->testtask->name,                                 'type' => 'title' ,  'sortType' => true, 'flex' => 1, 'link' => array('module' => 'testtask', 'method' => 'view', 'params' => 'testtaskID={id}'));
$config->block->testtask->dtable->fieldList['product'] = array('name' => 'productName',    'title' => $lang->testtask->product,                              'type' => 'string' , 'sortType' => true, 'link' => array('module' => 'product', 'method' => 'browse', 'params' => 'productID={product}'));
$config->block->testtask->dtable->fieldList['build']   = array('name' => 'executionBuild', 'title' => "{$lang->testtask->execution}/{$lang->build->common}", 'type' => 'string' , 'sortType' => true, 'link' => array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}'));
$config->block->testtask->dtable->fieldList['begin']   = array('name' => 'begin',          'title' => $lang->testtask->begin,                                'type' => 'date',    'sortType' => true);
$config->block->testtask->dtable->fieldList['end']     = array('name' => 'end',            'title' => $lang->testtask->end,                                  'type' => 'date',    'sortType' => true);
