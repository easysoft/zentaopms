<?php
global $lang;
$config->productplan->search['module'] = 'productplan';

$config->productplan->search['fields']['title']  = $lang->productplan->title;
$config->productplan->search['fields']['status'] = $lang->productplan->status;
$config->productplan->search['fields']['begin']  = $lang->productplan->begin;
$config->productplan->search['fields']['id']     = $lang->productplan->id;
$config->productplan->search['fields']['end']    = $lang->productplan->end;
$config->productplan->search['fields']['branch'] = $lang->productplan->branch;

$config->productplan->search['params']['id']     = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->productplan->search['params']['title']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->productplan->search['params']['branch'] = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->productplan->search['params']['status'] = array('operator' => '=',       'control' => 'select', 'values' => $lang->productplan->statusList);
$config->productplan->search['params']['begin']  = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->productplan->search['params']['end']    = array('operator' => '=',       'control' => 'date',  'values' => '');
