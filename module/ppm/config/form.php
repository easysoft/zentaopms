<?php
$config->ppm->form = new stdclass();
$config->ppm->form->create = array();
$config->ppm->form->create['sourceBranch']   = array('type' => 'string', 'required' => true);
$config->ppm->form->create['targetBranch']   = array('type' => 'string', 'required' => true);
$config->ppm->form->create['title']          = array('type' => 'string', 'required' => true);
$config->ppm->form->create['reviewer']       = array('type' => 'array', 'required' => true, 'default' => array(), 'filter' => 'join');
$config->ppm->form->create['desc']           = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->ppm->form->create['createdDate']    = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->ppm->form->create['reviewFlowID']   = array('type' => 'int', 'required' => false, 'default' => 0);
$config->ppm->form->create['sourceSHA']      = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->ppm->form->create['mergeTargetSHA'] = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');

$config->ppm->form->edit = array();
$config->ppm->form->edit['title']      = array('type' => 'string', 'required' => true);
$config->ppm->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->ppm->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->ppm->form->addReviewers = array();
$config->ppm->form->addReviewers['reviewer'] = array('type' => 'array', 'required' => true, 'default' => array());

$config->ppm->form->review = array();
$config->ppm->form->review['decision'] = array('type' => 'string', 'required' => true, 'default' => 'pending', 'filter' => 'trim');
$config->ppm->form->review['opinion']  = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
