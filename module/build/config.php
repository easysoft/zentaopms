<?php
$config->build = new stdclass();
$config->build->create = new stdclass();
$config->build->edit   = new stdclass();
$config->build->create->requiredFields = 'product,name,builder,date';
$config->build->edit->requiredFields   = 'product,name,builder,date';

$config->build->editor = new stdclass();
$config->build->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->build->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang;
$config->build->search['module']             = 'build';
$config->build->search['fields']['name']     = $lang->build->name;
$config->build->search['fields']['id']       = $lang->build->id;
$config->build->search['fields']['product']  = $lang->build->product;
$config->build->search['fields']['scmPath']  = $lang->build->scmPath;
$config->build->search['fields']['filePath'] = $lang->build->filePath;
$config->build->search['fields']['date']     = $lang->build->date;
$config->build->search['fields']['builder']  = $lang->build->builder;
$config->build->search['fields']['desc']     = $lang->build->desc;

$config->build->search['params']['name']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['product']  = array('operator' => '=',       'control' => 'select', 'values' => 'products');
$config->build->search['params']['scmPath']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['filePath'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->build->search['params']['date']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->build->search['params']['builder']  = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->build->search['params']['desc']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
