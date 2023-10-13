<?php
global $app;

$config->release->form = new stdclass();

$config->release->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->release->form->create['marker']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->release->form->create['build']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['stories']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['bugs']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['date']        = array('type' => 'date',     'required' => false, 'default' => null);
$config->release->form->create['desc']        = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->release->form->create['mailto']      = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->release->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());

$config->release->form->edit['name']    = array('type' => 'string',   'required' => true,  'default' => '');
$config->release->form->edit['marker']  = array('type' => 'int',      'required' => false, 'default' => 0);
$config->release->form->edit['build']   = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->edit['date']    = array('type' => 'date',     'required' => false, 'default' => null);
$config->release->form->edit['status']  = array('type' => 'string',   'required' => true,  'default' => 'normal');
$config->release->form->edit['desc']    = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->release->form->edit['mailto']  = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->release->form->edit['product'] = array('type' => 'int',      'required' => false, 'default' => 0);
$config->release->form->edit['branch']  = array('type' => 'int',      'required' => false, 'default' => 0);
