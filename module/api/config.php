<?php
$config->api = new stdClass();

$config->api->createlib = new stdclass();
$config->api->createlib->requiredFields = 'name';

$config->api->editlib = new stdclass();
$config->api->editlib->requiredFields = 'name';

$config->api->struct = new stdClass();
$config->api->struct->requiredFields = 'name,params';

$config->api->create = new stdclass();
$config->api->create->requiredFields = 'title,path';

$config->api->edit = new stdclass();
$config->api->edit->requiredFields = 'lib,title,path';

$config->api->createrelease = new stdclass();
$config->api->createrelease->requiredFields = 'version';

$config->api->editor = new stdclass();
$config->api->editor->createlib     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->editlib       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->create        = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->edit          = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->view          = array('id' => 'comment,lastComment', 'tools' => 'simple');
$config->api->editor->createRelease = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->createstruct  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->editstruct    = array('id' => 'desc', 'tools' => 'simpleTools');

global $lang;
$config->api->search['module']               = 'api';
$config->api->search['fields']['title']      = $lang->api->title;
$config->api->search['fields']['id']         = $lang->api->id;
$config->api->search['fields']['lib']        = $lang->api->lib;
$config->api->search['fields']['addedBy']    = $lang->api->addedBy;
$config->api->search['fields']['addedDate']  = $lang->api->addedDate;
$config->api->search['fields']['editedBy']   = $lang->api->editedBy;
$config->api->search['fields']['editedDate'] = $lang->api->editedDate;
$config->api->search['fields']['path']       = $lang->api->path;
$config->api->search['fields']['method']     = $lang->api->method;
$config->api->search['fields']['status']     = $lang->api->status;
$config->api->search['fields']['version']    = $lang->api->version;

$config->api->search['params']['title']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->api->search['params']['id']         = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->api->search['params']['lib']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->api->search['params']['addedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->api->search['params']['addedDate']  = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->api->search['params']['editedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->api->search['params']['editedDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->api->search['params']['path']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->api->search['params']['method']     = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->api->methodOptions);
$config->api->search['params']['status']     = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->api->statusOptions);
$config->api->search['params']['version']    = array('operator' => '>=',      'control' => 'input',  'values' => '');
