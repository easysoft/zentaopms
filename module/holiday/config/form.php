<?php
declare(strict_types=1);

$config->holiday->form = new stdclass();

$config->holiday->form->create = array();
$config->holiday->form->create['type']  = array('required' => true, 'type' => 'string', 'default' => '');
$config->holiday->form->create['begin'] = array('required' => true, 'type' => 'date');
$config->holiday->form->create['end']   = array('required' => true, 'type' => 'date');
$config->holiday->form->create['name']  = array('required' => false, 'type' => 'string');
$config->holiday->form->create['desc']  = array('required' => false, 'type' => 'string', 'default' => '');
