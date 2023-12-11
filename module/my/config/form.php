<?php
$config->my->form = new stdclass();
$config->my->form->changePassword = array();
$config->my->form->changePassword['originalPassword'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['password1']        = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['password2']        = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['passwordLength']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->my->form->changePassword['passwordStrength'] = array('required' => false, 'type' => 'int',    'default' => 0);

$config->my->form->manageContacts = array();
$config->my->form->manageContacts['listName'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->manageContacts['userList'] = array('required' => true,  'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->my->form->manageContacts['public']   = array('required' => false, 'type' => 'int',    'default' => 0);
