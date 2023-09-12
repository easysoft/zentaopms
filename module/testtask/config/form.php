<?php
declare(strict_types=1);
global $lang, $app;

$config->testtask->form = new stdclass();
$config->testtask->form->create = array();
$config->testtask->form->create['product']    = array('required' => true,  'type' => 'int',    'default' => '');
$config->testtask->form->create['execution']  = array('required' => false, 'type' => 'int',    'default' => 0);
$config->testtask->form->create['build']      = array('required' => true,  'type' => 'string', 'default' => '');
$config->testtask->form->create['type']       = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->testtask->form->create['owner']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->testtask->form->create['begin']      = array('required' => true,  'type' => 'date',   'default' => '');
$config->testtask->form->create['end']        = array('required' => true,  'type' => 'date',   'default' => '');
$config->testtask->form->create['status']     = array('required' => true,  'type' => 'string', 'default' => 'wait');
$config->testtask->form->create['testreport'] = array('required' => false, 'type' => 'int',    'default' => '0');
$config->testtask->form->create['name']       = array('required' => true,  'type' => 'string', 'default' => 0);
$config->testtask->form->create['pri']        = array('required' => false, 'type' => 'int',    'default' => 3);
$config->testtask->form->create['desc']       = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
$config->testtask->form->create['mailto']     = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');

$config->testtask->form->edit = array();
$config->testtask->form->edit['product']     = array('required' => true,  'type' => 'int',    'default' => '');
$config->testtask->form->edit['execution']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->testtask->form->edit['build']       = array('required' => true,  'type' => 'string', 'default' => '');
$config->testtask->form->edit['type']        = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->testtask->form->edit['owner']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->testtask->form->edit['begin']       = array('required' => true,  'type' => 'date',   'default' => '');
$config->testtask->form->edit['end']         = array('required' => true,  'type' => 'date',   'default' => '');
$config->testtask->form->edit['status']      = array('required' => true,  'type' => 'string', 'default' => 'wait');
$config->testtask->form->edit['testreport']  = array('required' => false, 'type' => 'int',    'default' => '0');
$config->testtask->form->edit['name']        = array('required' => true,  'type' => 'string', 'default' => 0);
$config->testtask->form->edit['pri']         = array('required' => false, 'type' => 'int',    'default' => 3);
$config->testtask->form->edit['desc']        = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
$config->testtask->form->edit['mailto']      = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->testtask->form->edit['deleteFiles'] = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');

$config->testtask->form->start = array();
$config->testtask->form->start['status']    = array('required' => true,  'type' => 'string', 'default' => 'doing');
$config->testtask->form->start['uid']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->testtask->form->start['comment']   = array('required' => false, 'type' => 'string', 'default' => '', 'control' => 'editor');
$config->testtask->form->start['realBegan'] = array('required' => true,  'type' => 'date',   'default' => date('Y-m-d'));

$config->testtask->form->close = array();
$config->testtask->form->close['status']           = array('required' => true,  'type' => 'string',   'default' => 'done');
$config->testtask->form->close['uid']              = array('required' => false, 'type' => 'string',   'default' => '');
$config->testtask->form->close['comment']          = array('required' => false, 'type' => 'string',   'default' => '', 'control' => 'editor');
$config->testtask->form->close['realFinishedDate'] = array('required' => true,  'type' => 'datetime', 'default' => date('Y-m-d H:i:s'));
$config->testtask->form->close['mailto']           = array('required' => false, 'type' => 'array',    'default' => array(), 'filter' => 'join');
