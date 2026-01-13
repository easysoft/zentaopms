<?php
$config->mr->form = new stdclass();
$config->mr->form->create = array();
$config->mr->form->create['sourceBranch']   = array('type' => 'string', 'required' => true);
$config->mr->form->create['targetBranch']   = array('type' => 'string', 'required' => true);
$config->mr->form->create['title']          = array('type' => 'string', 'required' => true);
$config->mr->form->create['reviewer']       = array('type' => 'array', 'required' => true, 'default' => array(), 'filter' => 'join');
$config->mr->form->create['desc']           = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->mr->form->create['createdDate']    = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->mr->form->create['reviewFlowID']   = array('type' => 'int', 'required' => false, 'default' => 0);
$config->mr->form->create['sourceSHA']      = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->mr->form->create['mergeTargetSHA'] = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');

$config->mr->form->edit = array();
$config->mr->form->edit['title']      = array('type' => 'string', 'required' => true);
$config->mr->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->mr->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->mr->form->addReviewers = array();
$config->mr->form->addReviewers['reviewer'] = array('type' => 'array', 'required' => true, 'default' => array());
