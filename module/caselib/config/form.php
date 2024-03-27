<?php
global $lang;
$config->caselib->form = new stdclass();
$config->caselib->form->create = array();
$config->caselib->form->create['name'] = array('type' => 'string',  'control' => 'text',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->caselib->form->create['desc'] = array('type' => 'string',  'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');

$config->caselib->form->edit = array();
$config->caselib->form->edit['name'] = array('type' => 'string', 'control' => 'text',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->caselib->form->edit['desc'] = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');
$config->caselib->form->edit['uid']  = array('type' => 'string', 'required' => false, 'default' => '');

global $app, $lang;
$app->loadLang('testcase');
$app->loadModuleConfig('testcase');

$config->caselib->testcase = new stdclass();
$config->caselib->testcase->form = new stdclass();
$config->caselib->testcase->form->create = $config->testcase->form->create;

$config->caselib->testcase->form->batchCreate = array();
$config->caselib->testcase->form->batchCreate['module']       = array('required' => false, 'type' => 'int',    'default' => 0);
$config->caselib->testcase->form->batchCreate['title']        = array('required' => true,  'type' => 'string', 'default' => '', 'base' => true);
$config->caselib->testcase->form->batchCreate['type']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->caselib->testcase->form->batchCreate['pri']          = array('required' => false, 'type' => 'int',    'default' => 3);
$config->caselib->testcase->form->batchCreate['precondition'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->caselib->testcase->form->batchCreate['keywords']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->caselib->testcase->form->batchCreate['stage']        = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'join');
