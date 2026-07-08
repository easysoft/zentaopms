<?php
$config->repobranchrule->form = new stdclass();
$config->repobranchrule->form->setBranchRule = array();
$config->repobranchrule->form->setBranchRule['forceReview']  = array('required' => false, 'type' => 'int', 'default' => 0);
$config->repobranchrule->form->setBranchRule['reviewFlowID'] = array('required' => false, 'type' => 'int', 'default' => 0);
