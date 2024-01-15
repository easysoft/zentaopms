<?php
global $app, $lang;
$config->product->form = new stdclass();
$config->product->form->create = array();
$config->product->form->create['program']        = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0,  'options' => array());
$config->product->form->create['line']           = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['name']           = array('type' => 'string',  'control' => 'text',         'required' => true,  'filter'  => 'trim');
$config->product->form->create['code']           = array('type' => 'string',  'control' => 'text',         'required' => false, 'filter'  => 'trim');
$config->product->form->create['PO']             = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',       'options' => array());
$config->product->form->create['QD']             = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',       'options' => array());
$config->product->form->create['RD']             = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',       'options' => array());
$config->product->form->create['reviewer']       = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',       'filter'  => 'join', 'options' => 'users');
$config->product->form->create['PMT']            = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',       'filter'  => 'join', 'options' => 'users');
$config->product->form->create['type']           = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal', 'options' => $lang->product->typeList);
$config->product->form->create['status']         = array('type' => 'string',  'control' => 'hidden',       'required' => false, 'default' => 'normal');
$config->product->form->create['desc']           = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '',        'width'  => 'full');
$config->product->form->create['acl']            = array('type' => 'string',  'control' => 'radio',        'required' => false, 'default' => 'private', 'width'  => 'full', 'options' => $lang->product->aclList);
$config->product->form->create['groups']         = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'filter' => 'join', 'width' => 'full', 'options' => array());
$config->product->form->create['whitelist']      = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'filter' => 'join', 'width' => 'full', 'options' => 'users');
$config->product->form->create['createdBy']      = array('type' => 'account', 'control' => '',             'required' => false, 'default' => (isset($app->user) && isset($app->user->account)) ? $app->user->account : '');
$config->product->form->create['createdDate']    = array('type' => 'string',  'control' => '',             'required' => false, 'default' => helper::now());
$config->product->form->create['createdVersion'] = array('type' => 'string',  'control' => '',             'required' => false, 'default' => $config->version);

$config->product->form->edit = array();
$config->product->form->edit['program']   = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0, 'options' => array());
$config->product->form->edit['line']      = array('type' => 'int',     'control' => 'select',       'required' => false, 'default' => 0, 'options' => array());
$config->product->form->edit['name']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'filter'  => 'trim');
$config->product->form->edit['code']      = array('type' => 'string',  'control' => 'text',         'required' => true,  'filter'  => 'trim');
$config->product->form->edit['PO']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',        'options' => array());
$config->product->form->edit['QD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',        'options' => array());
$config->product->form->edit['RD']        = array('type' => 'account', 'control' => 'select',       'required' => false, 'default' => '',        'options' => array());
$config->product->form->edit['PMT']       = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'filter'  => 'join', 'options' => 'users');
$config->product->form->edit['reviewer']  = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'filter'  => 'join', 'options' => 'users');
$config->product->form->edit['type']      = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal',  'options' => $lang->product->typeList);
$config->product->form->edit['status']    = array('type' => 'string',  'control' => 'select',       'required' => false, 'default' => 'normal',  'options' => $lang->product->statusList);
$config->product->form->edit['desc']      = array('type' => 'string',  'control' => 'editor',       'required' => false, 'default' => '',        'width'   => 'full');
$config->product->form->edit['acl']       = array('type' => 'string',  'control' => 'radio',        'required' => false, 'default' => 'private', 'width'   => 'full', 'options' => $lang->product->aclList);
$config->product->form->edit['groups']    = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'filter' => 'join', 'width' => 'full', 'options' => array());
$config->product->form->edit['whitelist'] = array('type' => 'array',   'control' => 'multi-select', 'required' => false, 'default' => '',        'width'   => 'full', 'filter'  => 'join', 'options' => 'users');

$config->product->form->batchEdit = array();
$config->product->form->batchEdit['program'] = array('type' => 'int',    'control' => 'select',    'width' => '200px', 'required' => false, 'default' => 0, 'options' => array());
$config->product->form->batchEdit['name']    = array('type' => 'string', 'control' => 'text',      'width' => '240px', 'required' => true,  'base'    => true);
$config->product->form->batchEdit['PO']      = array('type' => 'string', 'control' => 'select',    'width' => '128px', 'required' => false, 'default' => '',        'options' => array());
$config->product->form->batchEdit['QD']      = array('type' => 'string', 'control' => 'select',    'width' => '128px', 'required' => false, 'default' => '',        'options' => array());
$config->product->form->batchEdit['RD']      = array('type' => 'string', 'control' => 'select',    'width' => '128px', 'required' => false, 'default' => '',        'options' => array());
$config->product->form->batchEdit['type']    = array('type' => 'string', 'control' => 'select',    'width' => '128px', 'required' => false, 'default' => 'normal',  'options' => $lang->product->typeList);
$config->product->form->batchEdit['status']  = array('type' => 'string', 'control' => 'select',    'width' => '128px', 'required' => false, 'default' => 'normal',  'options' => $lang->product->statusList);
$config->product->form->batchEdit['acl']     = array('type' => 'string', 'control' => 'radioList', 'width' => '120px', 'required' => false, 'default' => 'private', 'options' => $lang->product->abbr->aclList);

$config->product->form->close = array();
$config->product->form->close['status']     = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => 'close');
$config->product->form->close['closedDate'] = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => date('Y-m-d'));

$config->product->form->activate = array();
$config->product->form->activate['status'] = array('type' => 'string', 'control' => 'hidden', 'required' => false, 'default' => 'normal');

$config->product->form->manageLine = array();
$config->product->form->manageLine['products'] = array('type' => 'array', 'control' => 'text', 'required' => false, 'default' => '');
$config->product->form->manageLine['programs'] = array('type' => 'array', 'control' => 'text', 'required' => false, 'default' => '');
$config->product->form->manageLine['modules']  = array('type' => 'array', 'control' => 'text', 'required' => false, 'default' => '');

if(empty($config->setCode))
{
    unset($config->product->form->create['code']);
    unset($config->product->form->edit['code']);
}

if($config->edition != 'ipd')
{
    unset($config->product->form->create['PMT']);
    unset($config->product->form->edit['PMT']);
}

if($config->systemMode != 'ALM' && $config->systemMode != 'PLM')
{
    unset($config->product->form->create['program'], $config->product->form->create['line']);
    unset($config->product->form->edit['program'], $config->product->form->edit['line']);
    unset($config->product->form->batchEdit['program']);
}
