<?php
declare(strict_types=1);

$config->holiday->form = new stdclass();

$config->holiday->form->create = array();
$config->holiday->form->create['type']  = array('required' => true, 'type' => 'string', 'default' => '');
$config->holiday->form->create['begin'] = array('required' => true, 'type' => 'date');
$config->holiday->form->create['end']   = array('required' => true, 'type' => 'date');
$config->holiday->form->create['name']  = array('required' => false, 'type' => 'string');
$config->holiday->form->create['desc']  = array('required' => false, 'type' => 'string', 'default' => '');

$config->holiday->form->edit = array();
$config->holiday->form->edit['type']  = array('required' => true, 'type' => 'string', 'default' => '');
$config->holiday->form->edit['begin'] = array('required' => true, 'type' => 'date');
$config->holiday->form->edit['end']   = array('required' => true, 'type' => 'date');
$config->holiday->form->edit['name']  = array('required' => false, 'type' => 'string');
$config->holiday->form->edit['desc']  = array('required' => false, 'type' => 'string', 'default' => '');
