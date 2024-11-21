<?php
$config->system->form = new stdclass();
$config->system->form->editDomain['customDomain'] = array('type' => 'string',   'required' => true,  'default' => '');
$config->system->form->editDomain['certPem']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->system->form->editDomain['certKey']      = array('type' => 'string',   'required' => false, 'default' => '');

$config->system->form->create['integrated']  = array('type' => 'int', 'required' => false, 'default' => 0);
$config->system->form->create['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->system->form->create['children']    = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->system->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->system->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());

$config->system->form->edit['name']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->system->form->edit['children']   = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->system->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->system->form->edit['editedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
