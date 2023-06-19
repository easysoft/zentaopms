<?php
declare(strict_types=1);
global $lang, $app;

$config->company->form = new stdclass();

$config->company->form->edit = array();
$config->company->form->edit['name']     = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->company->form->edit['phone']    = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['fax']      = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['address']  = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['zipcode']  = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['website']  = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['backyard'] = array('required' => false,  'type' => 'string', 'default' => '');
$config->company->form->edit['guest']    = array('required' => false,  'type' => 'int', 'default' => 0);
