<?php
declare(strict_types=1);

$config->todo->create->form = array();
$config->todo->create->form['type']         = array('required' => true,  'type' => 'string');
$config->todo->create->form['name']         = array('required' => true,  'type' => 'string', 'default'  => '');
$config->todo->create->form['story']        = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->create->form['task']         = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->create->form['bug']          = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->create->form['status']       = array('required' => true,  'type' => 'string');
$config->todo->create->form['pri']          = array('required' => true,  'type' => 'int');
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
$config->todo->create->form['cycle']        = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->create->form['config']       = array('required' => false, 'type' => 'array',   'default' => array());

$config->todo->batchCreate = new stdclass();
$config->todo->batchCreate->form = array();
$config->todo->batchCreate->form['type']       = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['pri']        = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['name']       = array('required' => false, 'type' => 'string', 'base' => true);
$config->todo->batchCreate->form['desc']       = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['assignedTo'] = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['begin']      = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['end']        = array('required' => false, 'type' => 'string');
$config->todo->batchCreate->form['switchTime'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchCreate->form['vision']     = array('required' => false, 'type' => 'string', 'default' => $this->config->vision);

$config->todo->edit->form = array();
$config->todo->edit->form['name']         = array('required' => true,  'type' => 'string',  'default' => '');
$config->todo->edit->form['status']       = array('required' => true,  'type' => 'string');
$config->todo->edit->form['pri']          = array('required' => true,  'type' => 'int');
$config->todo->edit->form['type']         = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->edit->form['date']         = array('required' => false, 'type' => 'string',  'default' => helper::today());
$config->todo->edit->form['begin']        = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->edit->form['end']          = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->edit->form['private']      = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->edit->form['assignedTo']   = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->edit->form['objectID']     = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->edit->form['desc']         = array('required' => false, 'type' => 'string',  'default' => '', 'control' => 'editor');
$config->todo->edit->form['config']       = array('required' => false, 'type' => 'array',   'default' => array());

$config->todo->assignTo = new stdclass();
$config->todo->assignTo->form = array();
$config->todo->assignTo->form['assignedBy']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->assignTo->form['assignedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->todo->assignTo->form['date']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->assignTo->form['begin']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->todo->assignTo->form['end']          = array('required' => false, 'type' => 'int',    'default' => 0);
$config->todo->assignTo->form['assignedTo']   = array('required' => true,  'type' => 'string');

$config->todo->batchClose = new stdclass();
$config->todo->batchClose->form = array();
$config->todo->batchClose->form['todoIdList'] = array('required' => true, 'type' => 'array');

$config->todo->batchEdit = new stdclass();
$config->todo->batchEdit->form = array();
$config->todo->batchEdit->form['id']         = array('required' => true,  'type' => 'int', 'base' => true);
$config->todo->batchEdit->form['date']       = array('required' => true,  'type' => 'date');
$config->todo->batchEdit->form['type']       = array('required' => true,  'type' => 'string');
$config->todo->batchEdit->form['pri']        = array('required' => true,  'type' => 'int');
$config->todo->batchEdit->form['name']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchEdit->form['assignedTo'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchEdit->form['begin']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchEdit->form['end']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->todo->batchEdit->form['status']     = array('required' => true,  'type' => 'string');
$config->todo->batchEdit->form['story']      = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->batchEdit->form['task']       = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->batchEdit->form['bug']        = array('required' => false, 'type' => 'string', 'default'  => 0);
$config->todo->batchEdit->form['testtask']   = array('required' => false, 'type' => 'string', 'default'  => 0);

$config->todo->batchFinish = new stdclass();
$config->todo->batchFinish->form = array();
$config->todo->batchFinish->form['todoIdList'] = array('required' => true, 'type' => 'array');

$config->todo->editDate = new stdclass();
$config->todo->editDate->form = array();
$config->todo->editDate->form['date']       = array('required' => true, 'type' => 'string');
$config->todo->editDate->form['todoIdList'] = array('required' => true, 'type' => 'array');

$config->todo->export = new stdclass();
$config->todo->export->form = array();
$config->todo->export->form['exportType'] = array('required' => true, 'type' => 'string');
