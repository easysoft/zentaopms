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
