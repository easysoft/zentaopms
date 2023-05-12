<?php
global $lang;
$config->product->form = new stdclass();
$config->product->form->create = array();
$config->product->form->create['program']   = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->product->form->create['line']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->product->form->create['lineName']  = array('type' => 'string',  'control' => 'text',         'required' => false, 'default' => '', 'filter' => 'trim');
$config->product->form->create['name']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'default' => '', 'filter' => 'trim');
$config->product->form->create['code']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'default' => '', 'filter' => 'trim');
$config->product->form->create['PO']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['QD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['RD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['reviewer']  = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'options' => 'users');
$config->product->form->create['type']      = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal', 'options' => $lang->product->typeList);
$config->product->form->create['status']    = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'normal');
$config->product->form->create['desc']      = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '', 'width' => 'full');
$config->product->form->create['acl']       = array('type' => 'string',  'control' => 'radio',        'required' => false, 'default' => 'private', 'width' => 'full', 'options' => $lang->product->aclList);
$config->product->form->create['whitelist'] = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'width' => 'full', 'options' => 'users');
if($config->systemMode != 'ALM') unset($config->product->form->create['program'], $config->product->form->create['line'], $config->product->form->create['lineName']);

$config->product->form->edit = array();
$config->product->form->edit['program']   = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->product->form->edit['line']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->product->form->edit['name']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'default' => '', 'filter' => 'trim');
$config->product->form->edit['code']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'default' => '', 'filter' => 'trim');
$config->product->form->edit['PO']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->edit['QD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->edit['RD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->edit['reviewer']  = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'options' => 'users');
$config->product->form->edit['type']      = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal', 'options' => $lang->product->typeList);
$config->product->form->edit['status']    = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal', 'options' => $lang->product->statusList);
$config->product->form->edit['desc']      = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '');
$config->product->form->edit['acl']       = array('type' => 'string',  'control' => 'radio',        'required' => false, 'default' => 'private', 'options' => $lang->product->aclList);
$config->product->form->edit['whitelist'] = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '', 'filter' => 'join', 'options' => 'users');
if($config->systemMode != 'ALM') unset($config->product->form->edit['program'], $config->product->form->edit['line']);

$config->product->form->batchEdit = array();
$config->product->form->batchEdit['program']       = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => 0, 'options' => array());
$config->product->form->batchEdit['name']          = array('type' => 'array', 'control' => 'text',     'required' => true,  'default' => '');
$config->product->form->batchEdit['line']          = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => 0,        'options' => array());
$config->product->form->batchEdit['PO']            = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => '',       'options' => array());
$config->product->form->batchEdit['QD']            = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => '',       'options' => array());
$config->product->form->batchEdit['RD']            = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => '',       'options' => array());
$config->product->form->batchEdit['type']          = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => 'normal', 'options' => $lang->product->typeList);
$config->product->form->batchEdit['status']        = array('type' => 'array', 'control' => 'select',   'required' => false, 'default' => 'normal', 'options' => $lang->product->statusList);
$config->product->form->batchEdit['desc']          = array('type' => 'array', 'control' => 'textarea', 'required' => false, 'default' => '');
$config->product->form->batchEdit['acl']           = array('type' => 'array', 'control' => 'radio',    'required' => false, 'default' => 'private', 'options' => $lang->product->aclList);
if($config->systemMode != 'ALM') unset($config->product->form->batchEdit['program'], $config->product->form->batchEdit['line']);

$config->product->form->close = array();
$config->product->form->close['status'] = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => 'close');

$config->product->form->manageLine = array();
$config->product->form->manageLine['products'] = array('type' => 'array', 'control' => 'text', 'required' => false, 'default' => '');
$config->product->form->manageLine['programs'] = array('type' => 'array', 'control' => 'text', 'required' => false, 'default' => '');
