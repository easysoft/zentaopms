<?php
$config->reporeviewflow->form = new stdclass();
$config->reporeviewflow->form->createReviewFlow = array();
$config->reporeviewflow->form->createReviewFlow['name']               = array('required' => true,  'type' => 'string', 'default' => '',        'filter' => 'trim');
$config->reporeviewflow->form->createReviewFlow['desc']               = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->reporeviewflow->form->createReviewFlow['aiReview']           = array('required' => true,  'type' => 'string', 'default' => 'disable', 'filter' => 'trim');
$config->reporeviewflow->form->createReviewFlow['aiReviewScores']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->reporeviewflow->form->createReviewFlow['defaultReviewers']   = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->reporeviewflow->form->createReviewFlow['specifiedReviewers'] = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->reporeviewflow->form->createReviewFlow['minReviewers']       = array('required' => false, 'type' => 'int',    'default' => 0);
$config->reporeviewflow->form->createReviewFlow['addressOption']      = array('required' => true,  'type' => 'string', 'default' => 'noNeedToSolve',   'filter' => 'trim');
$config->reporeviewflow->form->createReviewFlow['issueType']          = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->reporeviewflow->form->createReviewFlow['newCommits']         = array('required' => true,  'type' => 'string', 'default' => 'defaultApproval', 'filter' => 'trim');
$config->reporeviewflow->form->createReviewFlow['mergeOptions']       = array('required' => true,  'type' => 'array', 'default' => array('merge', 'squash', 'rebase', 'fast'), 'filter' => 'join');
$config->reporeviewflow->form->createReviewFlow['autoArchive']        = array('required' => true,  'type' => 'string', 'default' => 'disable', 'filter' => 'trim');
