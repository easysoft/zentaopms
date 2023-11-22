<?php
$config->dept->edit = new stdclass();
$config->dept->edit->requiredFields = 'name';

$config->dept->form = new stdclass();
$config->dept->form->edit = array();
$config->dept->form->edit['parent']  = array('type' => 'int',    'required' => false, 'default' => 0);
$config->dept->form->edit['name']    = array('type' => 'string', 'required' => true,  'default' => '');
$config->dept->form->edit['manager'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->dept->form = new stdclass();
$config->dept->form->manage = array();
$config->dept->form->manage['parentDeptID'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->dept->form->manage['depts']        = array('type' => 'array',  'required' => true,  'default' => array());
