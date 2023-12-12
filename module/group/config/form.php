<?php
$config->group->form = new stdclass();
$config->group->form->create = array();
$config->group->form->edit   = array();
$config->group->form->copy   = array();

$config->group->form->create['name']      = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->group->form->create['desc']      = array('type' => 'string', 'required' => false, 'filter'  => 'trim', 'default' => '');
$config->group->form->create['project']   = array('type' => 'int',    'required' => false, 'default' => 0);
$config->group->form->create['vision']    = array('type' => 'string', 'required' => false, 'default' => $config->vision);
$config->group->form->create['role']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->group->form->create['acl']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->group->form->create['developer'] = array('type' => 'string', 'required' => false, 'default' => '1');

$config->group->form->edit['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->group->form->edit['desc'] = array('type' => 'string', 'required' => false, 'filter' => 'trim', 'default' => '');

$config->group->form->copy['name'] = array('type' => 'string', 'required' => true,  'filter' => 'trim');
$config->group->form->copy['desc'] = array('type' => 'string', 'required' => false, 'filter' => 'trim', 'default' => '');
