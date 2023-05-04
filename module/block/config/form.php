<?php
$config->block->create = new stdclass();
$config->block->create->requiredFields = 'module,code,title';

$config->block->edit = new stdclass();
$config->block->edit->requiredFields = 'module,code,title';

$config->block->form = new stdclass();
$config->block->form->create = array();
$config->block->form->create['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->create['grid']   = array('type' => 'int',    'required' => false, 'default' => '4');
$config->block->form->create['hidden'] = array('type' => 'int',    'required' => false, 'default' => '0');
$config->block->form->create['params'] = array('type' => 'array',  'required' => false, 'default' => array());

$config->block->form->edit = array();
$config->block->form->edit['module'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['code']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['title']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->block->form->edit['grid']   = array('type' => 'int',    'required' => false, 'default' => '4');
$config->block->form->edit['params'] = array('type' => 'array',  'required' => false, 'default' => array());
