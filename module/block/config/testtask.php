<?php
global $lang, $app;
$app->loadLang('testtask');

$config->block->testtask = new stdclass();
$config->block->testtask->dtable = new stdclass();
$config->block->testtask->dtable->fieldList = array();
$config->block->testtask->dtable->fieldList['id']      = array('name' => 'id',             'title' => $lang->idAB,                                           'type' => 'id' ,     'sort' => 'number');
$config->block->testtask->dtable->fieldList['name']    = array('name' => 'name',           'title' => $lang->testtask->name,                                 'type' => 'title' ,  'sort' => true,  'flex' => 1, 'link' => array('module' => 'testtask', 'method' => 'view', 'params' => 'testtaskID={id}'));
$config->block->testtask->dtable->fieldList['product'] = array('name' => 'productName',    'title' => $lang->testtask->product,                              'type' => 'string' , 'sort' => true,  'link' => array('module' => 'product', 'method' => 'browse', 'params' => 'productID={product}'));
$config->block->testtask->dtable->fieldList['build']   = array('name' => 'executionBuild', 'title' => "{$lang->testtask->execution}/{$lang->build->common}", 'type' => 'string' , 'sort' => true,  'link' => array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}'));
$config->block->testtask->dtable->fieldList['begin']   = array('name' => 'begin',          'title' => $lang->testtask->begin,                                'type' => 'date',    'sort' => 'date');
$config->block->testtask->dtable->fieldList['end']     = array('name' => 'end',            'title' => $lang->testtask->end,                                  'type' => 'date',    'sort' => 'date');

$config->block->testtask->dtable->short = new stdclass();
$config->block->testtask->dtable->short->fieldList['id']    = $config->block->testtask->dtable->fieldList['id'];
$config->block->testtask->dtable->short->fieldList['name']  = $config->block->testtask->dtable->fieldList['name'];
$config->block->testtask->dtable->short->fieldList['end']   = $config->block->testtask->dtable->fieldList['end'];
