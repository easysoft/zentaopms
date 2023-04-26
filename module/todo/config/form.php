<?php
declare(strict_types=1);

$config->todo->createform = array();
$config->todo->createform['type']       = array('required' => true, 'type' => 'string');
$config->todo->createform['name']       = array('required' => true, 'type' => 'string');
$config->todo->createform['status']     = array('required' => true, 'type' => 'string');
$config->todo->createform['pri']        = array('required' => true, 'type' => 'int');

$config->todo->createform['date']         = array('required' => false, 'type' => 'string',  'default' => helper::today());
$config->todo->createform['begin']        = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->createform['end']          = array('required' => false, 'type' => 'int',     'default' => 2400);
$config->todo->createform['private']      = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->createform['assignedDate'] = array('required' => false, 'type' => 'string',  'default' => helper::now());
$config->todo->createform['assignedTo']   = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->createform['assignedBy']   = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->createform['vision']       = array('required' => false, 'type' => 'string',  'default' => $this->config->vision);
$config->todo->createform['idvalue']      = array('required' => false, 'type' => 'int',     'default' => 0);
$config->todo->createform['desc']         = array('required' => false, 'type' => 'string',  'default' => '');
$config->todo->createform['uid']          = array('required' => false, 'type' => 'string',  'default' => '');
