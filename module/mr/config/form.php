<?php
$config->mr->form = new stdclass();
$config->mr->form->create = array();
$config->mr->form->create['sourceBranch'] = array('type' => 'string', 'required' => true);
$config->mr->form->create['targetBranch'] = array('type' => 'string', 'required' => true);
$config->mr->form->create['title']        = array('type' => 'string', 'required' => true);
$config->mr->form->create['reviewer']     = array('type' => 'array', 'required' => true, 'default' => array(), 'filter' => 'join');
$config->mr->form->create['desc']         = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->mr->form->create['createdDate']  = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->mr->form->create['reviewFlowID'] = array('type' => 'int', 'required' => false, 'default' => 0);

$config->mr->form->edit = array();
$config->mr->form->edit['title']              = array('type' => 'string', 'required' => true);
$config->mr->form->edit['assignee']           = array('type' => 'string', 'required' => true);
$config->mr->form->edit['repoID']             = array('type' => 'int',    'required' => true);
$config->mr->form->edit['targetBranch']       = array('type' => 'string', 'required' => true);
$config->mr->form->edit['needCI']             = array('type' => 'int',    'required' => false, 'default' => 0);
$config->mr->form->edit['removeSourceBranch'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->mr->form->edit['squash']             = array('type' => 'int',    'required' => false, 'default' => 0);
$config->mr->form->edit['jobID']              = array('type' => 'int',    'required' => false, 'default' => 0);
$config->mr->form->edit['description']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->mr->form->edit['editedDate']         = array('type' => 'string', 'required' => false, 'default' => helper::now());
