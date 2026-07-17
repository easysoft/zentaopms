<?php
declare(strict_types=1);

$config->repo->form = new stdclass();

$config->repo->form->create = array();
$config->repo->form->create['space']          = array('required' => true,  'type' => 'int', 'default' => 0);
$config->repo->form->create['product']        = array('required' => true,  'type' => 'array');
$config->repo->form->create['projects']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->repo->form->create['SCM']            = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['serviceHost']    = array('required' => false, 'type' => 'int');
$config->repo->form->create['serviceProject'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['name']           = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['path']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encoding']       = array('required' => false, 'type' => 'string', 'default' => 'utf-8');
$config->repo->form->create['client']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['account']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['password']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encrypt']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['desc']           = array('required' => false, 'type' => 'string', 'default' => '');

$config->repo->form->edit = array();
$config->repo->form->edit['space']         = array('required' => true,  'type' => 'int', 'default' => 0);
$config->repo->form->edit['product']       = array('required' => true,  'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repo->form->edit['name']          = array('required' => true,  'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->edit['desc']          = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->edit['members']       = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->repo->form->edit['acl']           = array('required' => true,  'type' => 'string', 'default' => 'open');
$config->repo->form->edit['defaultBranch'] = array('required' => true,  'type' => 'string', 'default' => 'main');

$config->repo->form->createRepo = array();
$config->repo->form->createRepo['space']   = array('required' => true,  'type' => 'int', 'default' => 0);
$config->repo->form->createRepo['product'] = array('required' => true,  'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repo->form->createRepo['name']    = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->createRepo['desc']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->createRepo['members'] = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->repo->form->createRepo['acl']     = array('required' => true,  'type' => 'string', 'default' => 'open');

$config->repo->form->createBranch = array();
$config->repo->form->createBranch['codeRepo']   = array('required' => true, 'type' => 'int');
$config->repo->form->createBranch['branchFrom'] = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repo->form->createBranch['branchName'] = array('required' => true, 'type' => 'string', 'filter' => 'trim');

$config->repo->form->createTag = array();
$config->repo->form->createTag['codeRepo'] = array('required' => true, 'type' => 'int');
$config->repo->form->createTag['tagName']  = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repo->form->createTag['tagFrom']  = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repo->form->createTag['comment']  = array('required' => true, 'type' => 'string', 'filter' => 'trim');

$config->repo->form->import = array();
$config->repo->form->import['origin']     = array('required' => true, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['providerID'] = array('required' => true, 'type' => 'int', 'default' => 0);
$config->repo->form->import['organize']   = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['repo']       = array('required' => false, 'type' => 'string', 'filter' => 'trim', 'default' => '');
$config->repo->form->import['account']    = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['password']   = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['path']       = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['slug']       = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['name']       = array('required' => true, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['space']      = array('required' => true, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['product']    = array('required' => true, 'type' => 'array', 'default' => array(), 'filter' => 'join');
$config->repo->form->import['desc']       = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['mirror']     = array('required' => true, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['acl']        = array('required' => true, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->import['members']    = array('required' => false, 'type' => 'array', 'default' => array(), 'filter' => 'join');

$config->repo->form->createWebhook = array();
$config->repo->form->createWebhook['name']         = array('required' => true,  'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->createWebhook['targetURL']    = array('required' => true,  'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->createWebhook['SSL']          = array('required' => false, 'type' => 'array');
$config->repo->form->createWebhook['key']          = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->repo->form->createWebhook['triggerEvent'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->createWebhook['customEvent']  = array('required' => false, 'type' => 'array',  'default' => array());
$config->repo->form->createWebhook['desc']         = array('required' => false, 'type' => 'text',   'default' => '');

$config->repo->form->editWebhook = $config->repo->form->createWebhook;

$now = helper::now();
$config->repo->form->addBug = array();
$config->repo->form->addBug['file']          = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['revision']      = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['product']       = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['title']         = array('required' => true,  'type' => 'string');
$config->repo->form->addBug['begin']         = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['end']           = array('required' => true,  'type' => 'int');
$config->repo->form->addBug['branch']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['execution']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['pri']           = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['severity']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['module']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->repo->form->addBug['repoType']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['assignedTo']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['steps']         = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
$config->repo->form->addBug['fromReversion'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->addBug['severity']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->repo->form->addBug['openedDate']    = array('required' => false, 'type' => 'string', 'default' => $now);
$config->repo->form->addBug['assignedDate']  = array('required' => false, 'type' => 'string', 'default' => $now);
$config->repo->form->addBug['openedBuild']   = array('required' => false, 'type' => 'string', 'default' => 'trunk');
