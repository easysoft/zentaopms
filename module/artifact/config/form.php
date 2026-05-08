<?php
$config->artifact->form = new stdclass();

$config->artifact->form->create = array();
$config->artifact->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->artifact->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->artifact->form->edit = array();
$config->artifact->form->edit['name']       = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->artifact->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->artifact->form->createDir = array();
$config->artifact->form->createDir['name']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->artifact->form->createDir['format'] = array('type' => 'string', 'required' => false, 'default' => 'generic', 'filter' => 'trim');
