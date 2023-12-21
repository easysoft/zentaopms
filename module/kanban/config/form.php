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

$config->kanban->form->setting['showWIP']     = array('type' => 'string',   'required' => false, 'default' => '');
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

$config->kanban->form->createLane['name']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->createLane['mode']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->createLane['otherLane']      = array('type' => 'int',      'required' => false, 'default' => '');
$config->kanban->form->createLane['color']          = array('type' => 'string',   'required' => true,  'default' => '#7ec5ff');
$config->kanban->form->createLane['lastEditedTime'] = array('type' => 'datetime', 'required' => true,  'default' => $now);

$config->kanban->form->createColumn['name']    = array('type' => 'string', 'required' => true,  'default' => '');
$config->kanban->form->createColumn['color']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->kanban->form->createColumn['noLimit'] = array('type' => 'int',    'required' => false, 'default' => '');
$config->kanban->form->createColumn['limit']   = array('type' => 'int',    'required' => false, 'default' => -1);
$config->kanban->form->createColumn['group']   = array('type' => 'int',    'required' => true,  'default' => '');
$config->kanban->form->createColumn['parent']  = array('type' => 'int',    'required' => false, 'default' => 0);

$config->kanban->form->splitColumn['name']    = array('type' => 'string', 'required' => true,  'default' => '', 'base' => 'true');
$config->kanban->form->splitColumn['color']   = array('type' => 'string', 'required' => false, 'default' => '#333');
$config->kanban->form->splitColumn['limit']   = array('type' => 'int',    'required' => false, 'default' => '');
$config->kanban->form->splitColumn['noLimit'] = array('type' => 'int',    'required' => false, 'default' => '');

$config->kanban->form->createCard['name']         = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->createCard['pri']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->createCard['estimate']     = array('type' => 'float',    'required' => false, 'default' => '');
$config->kanban->form->createCard['assignedTo']   = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->kanban->form->createCard['begin']        = array('type' => 'date',     'required' => false, 'default' => '');
$config->kanban->form->createCard['end']          = array('type' => 'date',     'required' => false, 'default' => '');
$config->kanban->form->createCard['desc']         = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->kanban->form->createCard['createdBy']    = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->createCard['createdDate']  = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->kanban->form->createCard['assignedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->kanban->form->createCard['color']        = array('type' => 'string',   'required' => false, 'default' => '#fff');
$config->kanban->form->createCard['fromID']       = array('type' => 'int',      'required' => false, 'default' => 0);

$config->kanban->form->editCard['name']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->kanban->form->editCard['pri']            = array('type' => 'string',   'required' => false, 'default' => '');
$config->kanban->form->editCard['estimate']       = array('type' => 'float',    'required' => false, 'default' => '');
$config->kanban->form->editCard['progress']       = array('type' => 'float',    'required' => false, 'default' => '');
$config->kanban->form->editCard['assignedTo']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->kanban->form->editCard['begin']          = array('type' => 'date',     'required' => false, 'default' => '');
$config->kanban->form->editCard['end']            = array('type' => 'date',     'required' => false, 'default' => '');
$config->kanban->form->editCard['desc']           = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->kanban->form->editCard['lastEditedBy']   = array('type' => 'string',   'required' => true,  'default' => $app->user->account);
$config->kanban->form->editCard['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->kanban->form->batchCreateCard['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'base' => 'true');
$config->kanban->form->batchCreateCard['lane']       = array('type' => 'int',    'required' => true,  'default' => '');
$config->kanban->form->batchCreateCard['assignedTo'] = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->kanban->form->batchCreateCard['estimate']   = array('type' => 'float',  'required' => false, 'default' => '');
$config->kanban->form->batchCreateCard['begin']      = array('type' => 'date',   'required' => false, 'default' => '');
$config->kanban->form->batchCreateCard['end']        = array('type' => 'date',   'required' => false, 'default' => '');
$config->kanban->form->batchCreateCard['pri']        = array('type' => 'string', 'required' => false, 'default' => '');

$config->kanban->form->setWIP['limit']   = array('type' => 'int',    'required' => false, 'default' => -1);
$config->kanban->form->setWIP['noLimit'] = array('type' => 'int',    'required' => false, 'default' => '');

$config->kanban->form->setLane['name']  = array('type' => 'string',  'required' => false, 'default' => '');
$config->kanban->form->setLane['color'] = array('type' => 'string',  'required' => false, 'default' => '');

$config->kanban->form->setColumn['name']  = array('type' => 'string',  'required' => true,  'default' => '');
$config->kanban->form->setColumn['color'] = array('type' => 'string',  'required' => false, 'default' => '');
