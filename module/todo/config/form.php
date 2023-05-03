<?php
declare(strict_types=1);

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
$config->todo->batchEdit->form['names']      = array('required' => true, 'type' => 'array');

$config->todo->batchFinish = new stdclass;
$config->todo->batchFinish->form = array();
$config->todo->batchFinish->form['todoIDList'] = array('required' => true, 'type' => 'array');
