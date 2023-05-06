<?php
declare(strict_types=1);

$config->programplan->edit = new stdClass();
$config->programplan->edit->form = array();
$config->programplan->edit->form['begin']     = array('required' => false, 'type' => 'string',     'default' => '0000-00-00');
$config->programplan->edit->form['end']       = array('required' => false, 'type' => 'string',     'default' => '0000-00-00');
$config->programplan->edit->form['realBegan'] = array('required' => false, 'type' => 'string',     'default' => '0000-00-00');
$config->programplan->edit->form['realEnd']   = array('required' => false, 'type' => 'string',     'default' => '0000-00-00');
$config->programplan->edit->form['output']    = array('required' => false, 'type' => 'array',      'default' => array());




