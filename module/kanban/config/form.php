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

$config->kanban->form->closeSpace['status']         = array('type' => 'string',   'required' => true,  'default' => 'closed');
$config->kanban->form->closeSpace['closedBy']       = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->closeSpace['closedDate']     = array('type' => 'datetime', 'required' => true,  'default' => $now);
$config->kanban->form->closeSpace['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->closeSpace['lastEditedDate'] = array('type' => 'datetime', 'required' => true,  'default' => $now);

$config->kanban->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->create['showWIP']     = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->create['space']       = array('type' => 'int',      'required' => true,  'default' => '');
$config->kanban->form->create['owner']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->create['team']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter'  => 'join');
$config->kanban->form->create['fluidBoard']  = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->create['colWidth']    = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->create['minColWidth'] = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->create['maxColWidth'] = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->create['archived']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->create['performable'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->create['alignment']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->create['desc']        = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->kanban->form->create['whitelist']   = array('type' => 'array',    'required' => false, 'default' => '', 'filter'  => 'join');
$config->kanban->form->create['createdBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->kanban->form->edit['name']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->edit['space']          = array('type' => 'int',      'required' => true,  'default' => '');
$config->kanban->form->edit['owner']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->edit['team']           = array('type' => 'array',    'required' => false, 'default' => '', 'filter'  => 'join');
$config->kanban->form->edit['desc']           = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->kanban->form->edit['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->edit['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->kanban->form->setting['showWIP']     = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->setting['fluidBoard']  = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->setting['colWidth']    = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->setting['minColWidth'] = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->setting['maxColWidth'] = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->setting['archived']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->setting['performable'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->setting['alignment']   = array('type' => 'string',   'required' => false, 'default' => '');

$config->kanban->form->activate['status']         = array('type' => 'string',   'required' => true,  'default' => 'active');
$config->kanban->form->activate['activatedBy']    = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->activate['activatedDate']  = array('type' => 'datetime', 'required' => true,  'default' => $now);
$config->kanban->form->activate['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->activate['lastEditedDate'] = array('type' => 'datetime', 'required' => true,  'default' => $now);

$config->kanban->form->close['status']         = array('type' => 'string',   'required' => true,  'default' => 'closed');
$config->kanban->form->close['closedBy']       = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->close['closedDate']     = array('type' => 'datetime', 'required' => true,  'default' => $now);
$config->kanban->form->close['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->close['lastEditedDate'] = array('type' => 'datetime', 'required' => true,  'default' => $now);
