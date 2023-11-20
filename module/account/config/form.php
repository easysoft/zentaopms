<?php
$config->account->form = new stdclass();
$config->account->form->create = array();
$config->account->form->create['name']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->account->form->create['provider'] = array('type' => 'string', 'required' => true,  'default' => '');
$config->account->form->create['adminURI'] = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['account']  = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->account->form->create['password'] = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['email']    = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['mobile']   = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['type']     = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['status']   = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->account->form->create['extra']    = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
