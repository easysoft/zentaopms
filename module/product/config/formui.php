<?php
$config->product->create = new stdclass();
$config->product->edit   = new stdclass();
$config->product->create->requiredFields = 'name,code';
$config->product->edit->requiredFields   = 'name,code';

$config->product->create->fields['program']   = array('control' => 'select', 'options' => '');
$config->product->create->fields['name']      = array('control' => 'input');
$config->product->create->fields['code']      = array('control' => 'input');
$config->product->create->fields['PO']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['QD']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['RD']        = array('control' => 'select', 'options' => '');
$config->product->create->fields['reviewer']  = array('control' => 'select', 'options' => 'users');
$config->product->create->fields['type']      = array('control' => 'select', 'options' => $lang->product->typeList);
$config->product->create->fields['desc']      = array('control' => 'textarea');
$config->product->create->fields['acl']       = array('control' => 'radio', 'options' => $lang->product->aclList);
$config->product->create->fields['whitelist'] = array('control' => 'multi-select', 'options' => 'users');

$config->product->edit->fields['program']   = array('control' => 'select', 'options' => '');
$config->product->edit->fields['line']      = array('control' => 'select', 'options' => '');
$config->product->edit->fields['name']      = array('control' => 'input');
$config->product->edit->fields['code']      = array('control' => 'input');
$config->product->edit->fields['PO']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['QD']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['RD']        = array('control' => 'select', 'options' => '');
$config->product->edit->fields['reviewer']  = array('control' => 'select', 'options' => 'users');
$config->product->edit->fields['type']      = array('control' => 'select', 'options' => $lang->product->typeList);
$config->product->edit->fields['status']    = array('control' => 'select', 'options' => $lang->product->statusList);
$config->product->edit->fields['desc']      = array('control' => 'textarea');
$config->product->edit->fields['acl']       = array('control' => 'radio', 'options' => $lang->product->aclList);
$config->product->edit->fields['whitelist'] = array('control' => 'multi-select', 'options' => 'users');
