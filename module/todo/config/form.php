<?php
declare(strict_types=1);

if(!isset($config->todo->create)) $config->todo->create = new stdclass();
$config->todo->create->form = array();
$config->todo->create->form['type']       = array('required' => true, 'type' => 'string');
$config->todo->create->form['name']       = array('required' => true, 'type' => 'string');
$config->todo->create->form['status']     = array('required' => true, 'type' => 'string');
$config->todo->create->form['pri']        = array('required' => true, 'type' => 'int');

$config->todo->create->form['date']         = array('required' => false, 'type' => 'string',  'default' => helper::today());
$config->todo->create->form['begin']        = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->create->form['end']          = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->create->form['private']      = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->create->form['assignedDate'] = array('required' => false, 'type' => 'string',  'default' => helper::now());
$config->todo->create->form['assignedTo']   = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->create->form['assignedBy']   = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->create->form['vision']       = array('required' => false, 'type' => 'string',  'default' => $this->config->vision);
$config->todo->create->form['objectID']     = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->create->form['desc']         = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->create->form['uid']          = array('required' => false, 'type' => 'string',  'default' => '');

$config->todo->batchCreate = new stdClass;
$config->todo->batchCreate->form = array();
$config->todo->batchCreate->form['types']       = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['pris']        = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['names']       = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['descs']       = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['assignedTos'] = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['begins']      = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['ends']        = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['switchTime']  = array('required' => false, 'type' => 'array');
$config->todo->batchCreate->form['date']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchCreate->form['switchDate']  = array('required' => false, 'type' => 'string', 'default' => '');

$config->todo->edit->form = array();
$config->todo->edit->form['name'] = array('required' => true, 'type' => 'string');

$config->todo->assignTo = new stdClass();
$config->todo->assignTo->form = array();
$config->todo->assignTo->form['assignedBy']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->assignTo->form['assignedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->todo->assignTo->form['date']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->assignTo->form['begin']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->todo->assignTo->form['end']          = array('required' => false, 'type' => 'int',    'default' => 0);

$config->todo->assignTo->form['assignedTo']   = array('required' => true,  'type' => 'string');

$config->todo->batchClose = new stdclass;
$config->todo->batchClose->form = array();
$config->todo->batchClose->form['todoIDList'] = array('required' => true, 'type' => 'array');

$config->todo->batchEdit = new stdClass;
$config->todo->batchEdit->form = array();
$config->todo->batchEdit->form['todoIDList'] = array('required' => true, 'type' => 'array');

$config->todo->batchFinish = new stdclass;
$config->todo->batchFinish->form = array();
$config->todo->batchFinish->form['todoIDList'] = array('required' => true, 'type' => 'array');

$config->todo->editDate = new stdClass;
$config->todo->editDate->form = array();
$config->todo->editDate->form['date']       = array('required' => true, 'type' => 'string');
$config->todo->editDate->form['todoIDList'] = array('required' => true, 'type' => 'array');

$config->todo->export = new stdClass;
$config->todo->export->form = array();
$config->todo->export->form['exportType'] = array('required' => true, 'type' => 'string');
