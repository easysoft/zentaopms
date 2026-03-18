<?php
$config->repobranchrule->form = new stdclass();
$config->repobranchrule->form->setBranchRule = array();
$config->repobranchrule->form->setBranchRule['createUser']    = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repobranchrule->form->setBranchRule['deleteUser']    = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repobranchrule->form->setBranchRule['updateUser']    = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repobranchrule->form->setBranchRule['forcePushUser'] = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repobranchrule->form->setBranchRule['sourceBranch']  = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repobranchrule->form->setBranchRule['targetBranch']  = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
