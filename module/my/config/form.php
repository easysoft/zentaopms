<?php
$config->my->form = new stdclass();
$config->my->form->changePassword = array();
$config->my->form->changePassword['originalPassword'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['password1']        = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['password2']        = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->changePassword['passwordLength']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->my->form->changePassword['passwordStrength'] = array('required' => false, 'type' => 'int',    'default' => 0);

$config->my->form->editProfile = array();
$config->my->form->editProfile['account']          = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->editProfile['password1']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['password2']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['visions']          = array('required' => true,  'type' => 'array',  'default' => [], 'filter' => 'join');
$config->my->form->editProfile['realname']         = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->editProfile['join']             = array('required' => false, 'type' => 'date',   'default' => null);
$config->my->form->editProfile['email']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['gender']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['commiter']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['mobile']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['phone']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['qq']               = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['dingding']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['weixin']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['skype']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['whatsapp']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['slack']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['address']          = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['zipcode']          = array('required' => false, 'type' => 'string', 'default' => '');
$config->my->form->editProfile['verifyPassword']   = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->editProfile['passwordLength']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->my->form->editProfile['passwordStrength'] = array('required' => false, 'type' => 'int',    'default' => 0);

$config->my->form->manageContacts = array();
$config->my->form->manageContacts['listName'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->my->form->manageContacts['userList'] = array('required' => true,  'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->my->form->manageContacts['public']   = array('required' => false, 'type' => 'int',    'default' => 0);
