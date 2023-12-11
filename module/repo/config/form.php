<?php
declare(strict_types=1);

$config->repo->form = new stdclass();

$config->repo->form->create = common::formConfig('repo', 'create');
$config->repo->form->create['product']        = array('required' => true,  'type' => 'array');
$config->repo->form->create['projects']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->repo->form->create['SCM']            = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['serviceHost']    = array('required' => false, 'type' => 'int');
$config->repo->form->create['serviceProject'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['name']           = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['path']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encoding']       = array('required' => true,  'type' => 'string');
$config->repo->form->create['client']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['account']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['password']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encrypt']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['desc']           = array('required' => false, 'type' => 'string', 'default' => '');

$config->repo->form->edit = common::formConfig('repo', 'edit');
$config->repo->form->edit['product']        = array('required' => true,  'type' => 'array');
$config->repo->form->edit['projects']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->repo->form->edit['SCM']            = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->edit['serviceHost']    = array('required' => false, 'type' => 'int');
$config->repo->form->edit['serviceProject'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['name']           = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->edit['path']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['encoding']       = array('required' => true,  'type' => 'string');
$config->repo->form->edit['client']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['account']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['password']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['encrypt']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['desc']           = array('required' => false, 'type' => 'string', 'default' => '');


$config->repo->form->createRepo = array();
$config->repo->form->createRepo['product']     = array('required' => true,  'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repo->form->createRepo['projects']    = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repo->form->createRepo['serviceHost'] = array('required' => false, 'type' => 'int');
$config->repo->form->createRepo['namespace']   = array('required' => true,  'type' => 'int');
$config->repo->form->createRepo['name']        = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->createRepo['desc']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->createRepo['client']      = array('required' => false, 'type' => 'string', 'default' => 'git');
$config->repo->form->createRepo['SCM']         = array('required' => false, 'type' => 'string', 'default' => 'Gitlab');
$config->repo->form->createRepo['encoding']    = array('required' => false, 'type' => 'string', 'default' => 'utf-8');
$config->repo->form->createRepo['encrypt']     = array('required' => false, 'type' => 'string', 'default' => 'plain');

$config->repo->form->createBranch = array();
$config->repo->form->createBranch['repoID'] = array('required' => true, 'type' => 'int');
$config->repo->form->createBranch['from']   = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repo->form->createBranch['name']   = array('required' => true, 'type' => 'string', 'filter' => 'trim');
