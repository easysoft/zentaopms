<?php
$config->artifact->form = new stdclass();

$config->artifact->form->create = array();
$config->artifact->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->artifact->form->create['format']      = array('type' => 'string', 'required' => true, 'default' => 'file', 'filter' => 'trim');

$config->artifact->form->edit = array();
$config->artifact->form->edit['name']       = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->artifact->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->artifact->form->createDir = array();
$config->artifact->form->createDir['name'] = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');

$config->artifact->form->editDir = array();
$config->artifact->form->editDir['artifactID'] = array('type' => 'int', 'required' => true,  'default' => 0);
$config->artifact->form->editDir['parent']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->artifact->form->editDir['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');

$config->artifact->form->editArtifact = array();
$config->artifact->form->editArtifact['name'] = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');

$config->artifact->form->moveArtifact = array();
$config->artifact->form->moveArtifact['artifactID'] = array('type' => 'int', 'required' => true,  'default' => 0);
$config->artifact->form->moveArtifact['parent']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
