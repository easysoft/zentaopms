<?php
global $lang;

$config->doc = new stdclass();
$config->doc->createLib = new stdclass();
$config->doc->editLib   = new stdclass();
$config->doc->create    = new stdclass();
$config->doc->edit      = new stdclass();

$config->doc->createLib->requiredFields = 'name';
$config->doc->editLib->requiredFields   = 'name';
$config->doc->create->requiredFields = 'title';
$config->doc->edit->requiredFields   = 'title';

$config->doc->editor = new stdclass();
$config->doc->editor->create = array('id' => 'content', 'tools' => 'fullTools');
$config->doc->editor->edit   = array('id' => 'content,digest,comment', 'tools' => 'fullTools');

$config->doc->search['module']                   = 'doc';
$config->doc->search['fields']['title']          = $lang->doc->title;
$config->doc->search['fields']['id']             = $lang->doc->id;
$config->doc->search['fields']['keywords']       = $lang->doc->keywords;
$config->doc->search['fields']['product']        = $lang->doc->product;
$config->doc->search['fields']['project']        = $lang->doc->project;
$config->doc->search['fields']['type']           = $lang->doc->type;
$config->doc->search['fields']['module']         = $lang->doc->module;
$config->doc->search['fields']['lib']            = $lang->doc->lib;
$config->doc->search['fields']['digest']         = $lang->doc->digest;
$config->doc->search['fields']['content']        = $lang->doc->content;
$config->doc->search['fields']['url']            = $lang->doc->url;
$config->doc->search['fields']['addedBy']        = $lang->doc->addedBy;
$config->doc->search['fields']['addedDate']      = $lang->doc->addedDate;
$config->doc->search['fields']['editedBy']       = $lang->doc->editedBy;
$config->doc->search['fields']['editedDate']     = $lang->doc->editedDate;

$config->doc->search['params']['title']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['keywords']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['product']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['module']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['project']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['type']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->doc->types);
$config->doc->search['params']['lib']           = array('operator' => '=',       'control' => 'select', 'values' => '' );
$config->doc->search['params']['digest']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['content']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['url']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['addedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->doc->search['params']['addedDate']     = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->doc->search['params']['editedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->doc->search['params']['editedDate']    = array('operator' => '>=',      'control' => 'input',  'values' => '');
