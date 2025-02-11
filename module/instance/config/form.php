<?php
$config->instance->form = new stdclass();
$config->instance->form->create = array();
$config->instance->form->create['appType']     = array('type' => 'string',   'required' => true,  'default' => '');
$config->instance->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['url']         = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['account']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['password']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->instance->form->create['private']     = array('type' => 'string', 'required' => false, 'default' => uniqid());

$config->instance->form->edit = array();
$config->instance->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['account']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['password']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->instance->form->install = array();
$config->instance->form->install['storeAppType'] = array('type' => 'string', 'required' => false,  'default' => '');
$config->instance->form->install['type']         = array('type' => 'string', 'required' => false,  'default' => '');
$config->instance->form->install['customName']   = array('type' => 'string', 'required' => true,   'default' => '');
$config->instance->form->install['customDomain'] = array('type' => 'string', 'required' => false,  'default' => '');
$config->instance->form->install['version']      = array('type' => 'string', 'required' => false,  'default' => '');
$config->instance->form->install['dbType']       = array('type' => 'string', 'required' => false,  'default' => '');
$config->instance->form->install['dbService']    = array('type' => 'string', 'required' => false,  'default' => '', 'filter' => 'trim');
$config->instance->form->install['app_version']  = array('type' => 'string', 'required' => false,  'default' => '');

$config->instance->form->events = array();
$config->instance->form->events['component'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->instance->form->events['pod']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->instance->form->events['previous']  = array('type' => 'string', 'required' => false, 'default' => 0);
$config->instance->form->events['container'] = array('type' => 'string', 'required' => false, 'default' => '');