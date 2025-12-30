<?php
$config->repobranchrule->form->setBranchRule = array();
$config->repobranchrule->form->setBranchRule['radioForAllowCreate']           = array('required' => false, 'type' => 'string', 'default' => 'hasPriv');
$config->repobranchrule->form->setBranchRule['radioForAllowDelete']           = array('required' => false, 'type' => 'string', 'default' => 'hasPriv');
$config->repobranchrule->form->setBranchRule['radioForAllowUpdate']           = array('required' => false, 'type' => 'string', 'default' => 'hasPriv');
$config->repobranchrule->form->setBranchRule['radioForAllowForcePush']        = array('required' => false, 'type' => 'string', 'default' => 'hasPriv');
$config->repobranchrule->form->setBranchRule['radioForAllowMergeFrom']        = array('required' => false, 'type' => 'string', 'default' => 'all');
$config->repobranchrule->form->setBranchRule['radioForAllowMergeTo']          = array('required' => false, 'type' => 'string', 'default' => 'all');
$config->repobranchrule->form->setBranchRule['userAllowCreateGroup']          = array('required' => false, 'type' => 'array', 'default' => array());
$config->repobranchrule->form->setBranchRule['userAllowDeleteGroup']          = array('required' => false, 'type' => 'array', 'default' => array());
$config->repobranchrule->form->setBranchRule['userAllowUpdateGroup']          = array('required' => false, 'type' => 'array', 'default' => array());
$config->repobranchrule->form->setBranchRule['userAllowForcePushGroup']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->repobranchrule->form->setBranchRule['branchTypeAllowMergeFromGroup'] = array('required' => false, 'type' => 'array', 'default' => array());
$config->repobranchrule->form->setBranchRule['branchTypeAllowMergeToGroup']   = array('required' => false, 'type' => 'array', 'default' => array());