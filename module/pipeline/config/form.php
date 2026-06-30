<?php
$config->pipeline->form = new stdclass();

$config->pipeline->form->create = array();
$config->pipeline->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->pipeline->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->pipeline->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->pipeline->form->edit = $config->pipeline->form->create;
$config->pipeline->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
unset($config->pipeline->form->edit['createdDate']);

$config->pipeline->form->import = array();
$config->pipeline->form->import['providerID'] = array('type' => 'int',    'required' => true,  'default' => 0);
$config->pipeline->form->import['pipeline']   = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->pipeline->form->import['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->pipeline->form->import['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
