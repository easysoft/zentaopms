<?php
global $lang;
$config->product->form = new stdclass();
$config->product->form->create = array();
$config->product->form->create['program']   = array('type' => 'int',     'control' => 'select',       'required' => false, 'options' => array());
$config->product->form->create['line']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0, 'options' => array());
$config->product->form->create['lineName']  = array('type' => 'string',  'control' => 'input',        'required' => false, 'filter' => 'trim');
$config->product->form->create['name']      = array('type' => 'string',  'control' => 'input',        'required' => true,  'filter' => 'trim');
$config->product->form->create['code']      = array('type' => 'string',  'control' => 'input',        'required' => true,  'filter' => 'trim');
$config->product->form->create['PO']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'options' => '');
$config->product->form->create['QD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'options' => '');
$config->product->form->create['RD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'options' => '');
$config->product->form->create['reviewer']  = array('type' => 'string',  'control' => 'multi-select', 'required' => false, 'options' => 'users');
$config->product->form->create['type']      = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal', 'options' => $lang->product->typeList);
$config->product->form->create['status']    = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'normal');
$config->product->form->create['desc']      = array('type' => 'string',  'control' => 'textarea',     'required' => false);
$config->product->form->create['acl']       = array('type' => 'string',  'control' => 'acl',          'required' => false, 'default' => 'private', 'options' => $lang->product->aclList);
$config->product->form->create['whitelist'] = array('type' => 'string',  'control' => 'multi-select', 'required' => false, 'options' => 'users');
