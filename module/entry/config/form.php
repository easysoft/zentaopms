<?php
$config->entry->form = new stdClass();
$config->entry->form->create = array();
$config->entry->form->edit   = array();

$config->entry->form->create['name']       = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->entry->form->create['code']       = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->entry->form->create['account']    = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => '');
$config->entry->form->create['ip']         = array('type' => 'string', 'required' => false, 'filter'  => 'trim');
$config->entry->form->create['key']        = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => '');
$config->entry->form->create['allIP']      = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => 'off');
$config->entry->form->create['freePasswd'] = array('type' => 'int',    'required' => false);
$config->entry->form->create['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->entry->form->edit['name']       = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->entry->form->edit['code']       = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->entry->form->edit['account']    = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => '');
$config->entry->form->edit['ip']         = array('type' => 'string', 'required' => false, 'filter'  => 'trim');
$config->entry->form->edit['key']        = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => '');
$config->entry->form->edit['allIP']      = array('type' => 'string', 'required' => true,  'filter'  => 'trim', 'default' => 'off');
$config->entry->form->edit['freePasswd'] = array('type' => 'int',    'required' => false);
$config->entry->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
