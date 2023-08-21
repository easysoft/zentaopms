<?php
declare(strict_types=1);
global $app;

$config->testsuite->form = new stdclass();

$config->testsuite->form->create = array();
$config->testsuite->form->create['name']      = array('required' => true,  'type' => 'string',   'filter'  => 'trim');
$config->testsuite->form->create['desc']      = array('required' => false, 'type' => 'string',   'default' => '', 'control' => 'editor');
$config->testsuite->form->create['type']      = array('required' => false, 'type' => 'string',   'default' => 'private');
$config->testsuite->form->create['addedBy']   = array('required' => false, 'type' => 'string',   'default' => $app->user->account);
$config->testsuite->form->create['addedDate'] = array('required' => false, 'type' => 'datetime', 'default' => helper::now());

$config->testsuite->form->edit = array();
$config->testsuite->form->edit['name']           = array('required' => true,  'type' => 'string',   'filter'  => 'trim');
$config->testsuite->form->edit['desc']           = array('required' => false, 'type' => 'string',   'default' => '', 'control' => 'editor');
$config->testsuite->form->edit['type']           = array('required' => false, 'type' => 'string',   'default' => 'private');
$config->testsuite->form->edit['lastEditedBy']   = array('required' => false, 'type' => 'string',   'default' => $app->user->account);
$config->testsuite->form->edit['lastEditedDate'] = array('required' => false, 'type' => 'datetime', 'default' => helper::now());

$config->testsuite->form->linkCase = array();
$config->testsuite->form->linkCase['cases']    = array('required' => true, 'type' => 'array', 'default' => []);
$config->testsuite->form->linkCase['versions'] = array('required' => true, 'type' => 'array', 'default' => []);
