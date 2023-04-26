<?php
global $lang;

$config->zdisk = new stdclass();

$config->zdisk->root = array();
$config->zdisk->root['my']      = array('name' => $lang->my->common,      'type' => 'folder');
$config->zdisk->root['program'] = array('name' => $lang->program->common, 'type' => 'folder');
$config->zdisk->root['product'] = array('name' => $lang->product->common, 'type' => 'folder');
$config->zdisk->root['project'] = array('name' => $lang->project->common, 'type' => 'folder');
$config->zdisk->root['qa']      = array('name' => $lang->qa->common,      'type' => 'folder');

$config->zdisk->my = array();
$config->zdisk->my['todo']        = array('name' => $lang->user->todo,      'type' => 'file');
$config->zdisk->my['work']        = array('name' => $lang->my->work,        'type' => 'folder');
$config->zdisk->my['myproject']   = array('name' => $lang->projectCommon,   'type' => 'folder');
$config->zdisk->my['myexecution'] = array('name' => $lang->executionCommon, 'type' => 'folder');

$config->zdisk->work = array();
$config->zdisk->work['task']     = array('name' => $lang->user->task,     'type' => 'file');
$config->zdisk->work['story']    = array('name' => $lang->user->story,    'type' => 'file');
$config->zdisk->work['bug']      = array('name' => $lang->user->bug,      'type' => 'file');
$config->zdisk->work['case']     = array('name' => $lang->user->testCase, 'type' => 'file');
$config->zdisk->work['testtask'] = array('name' => $lang->user->testTask, 'type' => 'file');

$config->zdisk->myproject = array();
$config->zdisk->myproject['doing']      = array('name' => $lang->zdisk->doing,      'type' => 'file');
$config->zdisk->myproject['wait']       = array('name' => $lang->zdisk->wait,       'type' => 'file');
$config->zdisk->myproject['suspend']    = array('name' => $lang->zdisk->suspend,    'type' => 'file');
$config->zdisk->myproject['closed']     = array('name' => $lang->zdisk->closed,     'type' => 'file');
$config->zdisk->myproject['openedbyme'] = array('name' => $lang->zdisk->openedByMe, 'type' => 'file');

$config->zdisk->myexecution = array();
$config->zdisk->myexecution['undone'] = array('name' => $lang->zdisk->undone, 'type' => 'file');
$config->zdisk->myexecution['done']   = array('name' => $lang->zdisk->done,   'type' => 'file');
