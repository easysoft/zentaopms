<?php
global $lang, $app;
$config->build->form = new stdclass();

$now = helper::now();
$config->build->form->create['project']        = array('type' => 'int',       'required' => false, 'default' => 0);
$config->build->form->create['execution']      = array('type' => 'int',       'required' => $app->tab == 'execution', 'default' => 0);
$config->build->form->create['artifactRepoID'] = array('type' => 'int',       'required' => false, 'default' => 0);
$config->build->form->create['product']        = array('type' => 'int',       'required' => false, 'default' => 0);
$config->build->form->create['scmPath']        = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->create['filePath']       = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->create['desc']           = array('type' => 'string',    'required' => false, 'default' => '', 'control' => 'editor');
$config->build->form->create['builder']        = array('type' => 'string',    'required' => true,  'default' => '');
$config->build->form->create['name']           = array('type' => 'string',    'required' => true,  'default' => '', 'filter' => 'trim');
$config->build->form->create['branch']         = array('type' => 'array',     'required' => false, 'default' => array(), 'filter' => 'join');
$config->build->form->create['builds']         = array('type' => 'array',     'required' => false, 'default' => array(), 'filter' => 'join');
$config->build->form->create['bugs']           = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->create['stories']        = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->create['date']           = array('type' => 'date',      'required' => true,  'default' => helper::today());
$config->build->form->create['createdBy']      = array('type' => 'string',    'required' => false, 'default' => $app->user->account);
$config->build->form->create['createdDate']    = array('type' => 'datetime ', 'required' => false, 'default' => $now);

$config->build->form->edit['execution'] = array('type' => 'int',       'required' => $app->tab == 'execution', 'default' => 0);
$config->build->form->edit['product']   = array('type' => 'int',       'required' => true,  'default' => 0);
$config->build->form->edit['scmPath']   = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->edit['filePath']  = array('type' => 'string',    'required' => false, 'default' => '');
$config->build->form->edit['desc']      = array('type' => 'string',    'required' => false, 'default' => '', 'control' => 'editor');
$config->build->form->edit['builder']   = array('type' => 'string',    'required' => true,  'default' => '');
$config->build->form->edit['name']      = array('type' => 'string',    'required' => true,  'default' => '', 'filter' => 'trim');
$config->build->form->edit['branch']    = array('type' => 'array',     'required' => false, 'default' => array(), 'filter' => 'join');
$config->build->form->edit['builds']    = array('type' => 'array',     'required' => false, 'default' => array(), 'filter' => 'join');
$config->build->form->edit['date']      = array('type' => 'date',      'required' => true,  'default' => helper::today());
