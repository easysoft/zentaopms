<?php
declare(strict_types=1);

$config->artifactrepo->form = new stdclass();

$config->artifactrepo->form->create = common::formConfig('artifactrepo', 'create');
$config->artifactrepo->form->create['name']     = array('required' => true, 'type' => 'string');
$config->artifactrepo->form->create['products'] = array('required' => false, 'type' => 'array', 'default' => array());
$config->artifactrepo->form->create['serverID'] = array('required' => true, 'type' => 'int');
$config->artifactrepo->form->create['repoName'] = array('required' => true, 'type' => 'string');
$config->artifactrepo->form->create['type']     = array('required' => true, 'type' => 'string');
$config->artifactrepo->form->create['format']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->artifactrepo->form->create['status']   = array('required' => true, 'type' => 'string');

$config->artifactrepo->form->edit = common::formConfig('artifactrepo', 'edit');
$config->artifactrepo->form->edit['name']     = array('required' => true, 'type' => 'string');
$config->artifactrepo->form->edit['products'] = array('required' => false, 'type' => 'array', 'default' => array());
