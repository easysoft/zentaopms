<?php
$now = helper::now();
global $app;

$config->kanban->form = new stdclass();

$config->kanban->form->createSpace['type']      = array('type' => 'string',  'required' => true,  'default' => '');
$config->kanban->form->createSpace['name']      = array('type' => 'string',  'required' => true,  'default' => '');
$config->kanban->form->createSpace['owner']     = array('type' => 'string',  'required' => false, 'default' => '');
$config->kanban->form->createSpace['team']      = array('type' => 'array',   'required' => false, 'default' => '', 'filter'  => 'join');
$config->kanban->form->createSpace['whitelist'] = array('type' => 'array',   'required' => false, 'default' => '', 'filter'  => 'join');
$config->kanban->form->createSpace['desc']      = array('type' => 'string',  'required' => false, 'default' => '', 'control' => 'editor');

$config->kanban->form->editSpace = $config->kanban->form->createSpace;

$config->kanban->form->activateSpace['status']         = array('type' => 'string',   'required' => true,  'default' => 'active');
$config->kanban->form->activateSpace['activatedBy']    = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->activateSpace['activatedDate']  = array('type' => 'datetime', 'required' => true,  'default' => $now);
$config->kanban->form->activateSpace['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->activateSpace['lastEditedDate'] = array('type' => 'datetime', 'required' => true,  'default' => $now);
