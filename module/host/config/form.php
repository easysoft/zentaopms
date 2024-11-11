<?php
$config->host->form = new stdclass();
$config->host->form->create = array();
$config->host->form->create['name']       = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->create['cpuNumber']  = array('type' => 'int',    'required' => false,  'default' => 0);
$config->host->form->create['diskSize']   = array('type' => 'float',  'required' => false,  'default' => 0);
$config->host->form->create['memory']     = array('type' => 'float',  'required' => false,  'default' => 0);
$config->host->form->create['group']      = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->create['serverRoom'] = array('type' => 'int',    'required' => false,  'default' => '');
$config->host->form->create['osName']     = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->create['osVersion']  = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->create['intranet']   = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->create['extranet']   = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->create['status']     = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->create['type']       = array('type' => 'string', 'required' => false,  'default' => 'normal');
$config->host->form->create['desc']       = array('type' => 'string', 'required' => false,  'default' => '');

$config->host->form->edit = array();
$config->host->form->edit['name']       = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->edit['cpuNumber']  = array('type' => 'int',    'required' => false,  'default' => 0);
$config->host->form->edit['diskSize']   = array('type' => 'float',  'required' => false,  'default' => 0);
$config->host->form->edit['memory']     = array('type' => 'float',  'required' => false,  'default' => 0);
$config->host->form->edit['group']      = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->edit['serverRoom'] = array('type' => 'int',    'required' => false,  'default' => '');
$config->host->form->edit['osName']     = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->edit['osVersion']  = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->edit['intranet']   = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->edit['extranet']   = array('type' => 'string', 'required' => true,   'default' => '');
$config->host->form->edit['status']     = array('type' => 'string', 'required' => false,  'default' => '');
$config->host->form->edit['desc']       = array('type' => 'string', 'required' => false,  'default' => '');

$config->host->form->changeStatus = array();
$config->host->form->changeStatus['reason'] = array('type' => 'string', 'required' => true, 'default' => '', 'control' => 'editor');
