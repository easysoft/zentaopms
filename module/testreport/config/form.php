<?php
$now = helper::now();

$config->testreport->form = new stdclass();

global $app;
$config->testreport->form->create = array();
$config->testreport->form->create['tasks']       = array('type' => 'string',   'required' => true,  'default' => '0');
$config->testreport->form->create['begin']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->testreport->form->create['end']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->testreport->form->create['product']     = array('type' => 'int',      'required' => true,  'default' => 0);
$config->testreport->form->create['execution']   = array('type' => 'int',      'required' => true,  'default' => 0);
$config->testreport->form->create['objectID']    = array('type' => 'int',      'required' => true,  'default' => 0);
$config->testreport->form->create['objectType']  = array('type' => 'string',   'required' => true,  'default' => '');
$config->testreport->form->create['owner']       = array('type' => 'string',   'required' => true,  'default' => '');
$config->testreport->form->create['members']     = array('type' => 'array',    'required' => false, 'default' => array(''), 'filter' => 'join');
$config->testreport->form->create['title']       = array('type' => 'string',   'required' => true,  'default' => '');
$config->testreport->form->create['report']      = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->testreport->form->create['bugs']        = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->create['builds']      = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->create['cases']       = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->create['stories']     = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->testreport->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->testreport->form->edit = array();
$config->testreport->form->edit['tasks']       = array('type' => 'string',   'required' => true,  'default' => '0');
$config->testreport->form->edit['begin']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->testreport->form->edit['end']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->testreport->form->edit['product']     = array('type' => 'int',      'required' => true,  'default' => 0);
$config->testreport->form->edit['execution']   = array('type' => 'int',      'required' => true,  'default' => 0);
$config->testreport->form->edit['owner']       = array('type' => 'string',   'required' => true,  'default' => '');
$config->testreport->form->edit['members']     = array('type' => 'array',    'required' => false, 'default' => array(''), 'filter' => 'join');
$config->testreport->form->edit['title']       = array('type' => 'string',   'required' => true,  'default' => '');
$config->testreport->form->edit['report']      = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->testreport->form->edit['bugs']        = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->edit['builds']      = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->edit['cases']       = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->testreport->form->edit['stories']     = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
