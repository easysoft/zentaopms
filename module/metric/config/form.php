<?php
declare(strict_types=1);
global $lang, $app;

$config->metric->form = new stdclass();

$config->metric->form->create = array();
$config->metric->form->create['name']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->create['code']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->create['purpose']    = array('required' => true,  'type' => 'string');
$config->metric->form->create['scope']      = array('required' => true,  'type' => 'string');
$config->metric->form->create['object']     = array('required' => true,  'type' => 'string');
$config->metric->form->create['desc']       = array('required' => false,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->create['definition'] = array('required' => false,  'type' => 'string', 'filter' => 'trim');

$config->metric->form->edit = array();
$config->metric->form->edit['name']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->edit['code']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->edit['status']     = array('required' => true,  'type' => 'string');
$config->metric->form->edit['confirm']    = array('required' => true,  'type' => 'string');
$config->metric->form->edit['purpose']    = array('required' => true,  'type' => 'string');
$config->metric->form->edit['scope']      = array('required' => true,  'type' => 'string');
$config->metric->form->edit['object']     = array('required' => true,  'type' => 'string');
$config->metric->form->edit['desc']       = array('required' => false,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->edit['definition'] = array('required' => false,  'type' => 'string', 'filter' => 'trim');

$config->metric->form->change = array();
$config->metric->form->change['name']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->change['code']       = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->change['confirm']    = array('required' => true,  'type' => 'string');
$config->metric->form->change['status']     = array('required' => true,  'type' => 'string');
$config->metric->form->change['purpose']    = array('required' => true,  'type' => 'string');
$config->metric->form->change['scope']      = array('required' => true,  'type' => 'string');
$config->metric->form->change['object']     = array('required' => true,  'type' => 'string');
$config->metric->form->change['unit']       = array('required' => false,  'type' => 'string');
$config->metric->form->change['desc']       = array('required' => false,  'type' => 'string', 'filter' => 'trim');
$config->metric->form->change['definition'] = array('required' => false,  'type' => 'string', 'filter' => 'trim');
