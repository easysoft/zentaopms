<?php
global $app;

$config->release->form = new stdclass();

$config->release->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '');
$config->release->form->create['marker']      = array('type' => 'int',    'required' => false, 'default' => 0);
$config->release->form->create['build']       = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['stories']     = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['bugs']        = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['date']        = array('type' => 'date',   'required' => false, 'default' => null);
$config->release->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->release->form->create['mailto']      = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->release->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
