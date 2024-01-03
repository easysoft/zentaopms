<?php
$now = helper::now();

$config->task->form = new stdclass();
$config->task->form->team = new stdclass();
$config->task->form->testTask = new stdclass();

global $app;
$config->task->form->create = common::formConfig('task', 'create');
$config->task->form->create['execution']    = array('type' => 'int',      'required' => true,  'default' => 0);
$config->task->form->create['type']         = array('type' => 'string',   'required' => true,  'default' => '');
$config->task->form->create['assignedTo']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->create['module']       = array('type' => 'int',      'required' => false);
$config->task->form->create['story']        = array('type' => 'int',      'required' => false);
$config->task->form->create['mode']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->create['color']        = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->create['name']         = array('type' => 'string',   'required' => true,  'default' => '');
$config->task->form->create['pri']          = array('type' => 'int',      'required' => false, 'default' => $config->task->default->pri);
$config->task->form->create['estimate']     = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->create['desc']         = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->task->form->create['estStarted']   = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->create['deadline']     = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->create['vision']       = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->task->form->create['status']       = array('type' => 'string',   'required' => false, 'default' => 'wait');
$config->task->form->create['openedBy']     = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->create['openedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->create['version']      = array('type' => 'int',      'required' => false, 'default' => 1);
$config->task->form->create['storyVersion'] = array('type' => 'int',      'required' => false, 'default' => 1);
$config->task->form->create['uid']          = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->create['mailto']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');

$config->task->form->assign = array();
$config->task->form->assign['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->assign['assignedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->assign['left']           = array('type' => 'float',    'required' => true);
$config->task->form->assign['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->assign['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->task->form->cancel = array();
$config->task->form->cancel['status']  = array('type' => 'string', 'required' => false, 'default' => 'cancel');
$config->task->form->cancel['comment'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->task->form->manageTeam = array();
$config->task->form->manageTeam['status']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->manageTeam['estimate']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->manageTeam['left']           = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->manageTeam['consumed']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->manageTeam['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->manageTeam['assignedDate']   = array('type' => 'string',   'required' => false, 'default' => $now);

$config->task->form->edit = common::formConfig('task', 'edit');
$config->task->form->edit['name']           = array('type' => 'string',   'required' => true);
$config->task->form->edit['color']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['desc']           = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->task->form->edit['execution']      = array('type' => 'int',      'required' => true);
$config->task->form->edit['story']          = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->edit['module']         = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->edit['parent']         = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->edit['mailto']         = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->task->form->edit['mode']           = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['type']           = array('type' => 'string',   'required' => true);
$config->task->form->edit['status']         = array('type' => 'string',   'required' => true);
$config->task->form->edit['pri']            = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->edit['estStarted']     = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->edit['deadline']       = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->edit['estimate']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->edit['left']           = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->edit['consumed']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->edit['finishedBy']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['finishedDate']   = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->edit['canceledBy']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['canceledDate']   = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->edit['closedBy']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['closedReason']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->edit['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->edit['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->edit['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->edit['deleteFiles']    = array('type' => 'array',    'required' => false, 'default' => array());

$config->task->form->team->create = array();
$config->task->form->team->create['team']         = array('type' => 'array',  'required' => false, 'default' => array());
$config->task->form->team->create['teamSource']   = array('type' => 'array',  'required' => false, 'default' => array());
$config->task->form->team->create['teamEstimate'] = array('type' => 'array',  'required' => false, 'default' => array());
$config->task->form->team->create['teamConsumed'] = array('type' => 'array',  'required' => false, 'default' => array());
$config->task->form->team->create['teamLeft']     = array('type' => 'array',  'required' => false, 'default' => array());

$config->task->form->team->edit = $config->task->form->team->create;

$config->task->form->batchedit = common::formConfig('task', 'batchEdit');
$config->task->form->batchedit['id']             = array('type' => 'int',      'required' => false, 'default' => 0, 'base' => true);
$config->task->form->batchedit['module']         = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchedit['name']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->task->form->batchedit['color']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchedit['type']           = array('type' => 'string',   'required' => true,  'default' => '');
$config->task->form->batchedit['status']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchedit['pri']            = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchedit['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchedit['estimate']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->batchedit['consumed']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->batchedit['left']           = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->batchedit['estStarted']     = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->batchedit['deadline']       = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->batchedit['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->batchedit['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->task->form->batchcreate = common::formConfig('task', 'batchCreate');
$config->task->form->batchcreate['module']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchcreate['parent']        = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchcreate['story']         = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchcreate['name']          = array('type' => 'string',   'required' => false, 'default' => '', 'base' => true);
$config->task->form->batchcreate['color']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchcreate['type']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchcreate['version']       = array('type' => 'int',      'required' => false, 'default' => 1);
$config->task->form->batchcreate['storyVersion']  = array('type' => 'int',      'required' => false, 'default' => 1);
$config->task->form->batchcreate['assignedTo']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchcreate['estimate']      = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->batchcreate['estStarted']    = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->batchcreate['deadline']      = array('type' => 'date',     'required' => false, 'default' => null);
$config->task->form->batchcreate['desc']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->batchcreate['pri']           = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchcreate['lane']          = array('type' => 'int',      'required' => false, 'default' => 0);
$config->task->form->batchcreate['openedBy']      = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->batchcreate['openedDate']    = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->batchcreate['vision']        = array('type' => 'string',   'required' => false, 'default' => $config->vision);

$config->task->form->pause = array();
$config->task->form->pause['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->pause['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->pause['status']         = array('type' => 'string',   'required' => false, 'default' => 'pause');

$config->task->form->activate = array();
$config->task->form->activate['mode']           = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['left']           = array('type' => 'float',    'required' => true);
$config->task->form->activate['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['comment']        = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->task->form->activate['status']         = array('type' => 'string',   'required' => false, 'default' => 'doing');
$config->task->form->activate['activatedDate']  = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->activate['assignedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->activate['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->activate['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->activate['finishedBy']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['canceledBy']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['closedBy']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['closedReason']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->activate['finishedDate']   = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->activate['canceledDate']   = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->activate['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => null);

$config->task->form->start = array();
$config->task->form->start['status']         = array('type' => 'string',   'required' => false, 'default' => 'doing');
$config->task->form->start['consumed']       = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->start['left']           = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->start['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->start['realStarted']    = array('type' => 'datetime', 'required' => false, 'default' => null);
$config->task->form->start['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->start['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->task->form->finish = array();
$config->task->form->finish['realStarted']     = array('type' => 'datetime', 'required' => true);
$config->task->form->finish['left']            = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->finish['consumed']        = array('type' => 'float',    'required' => false, 'default' => 0);
$config->task->form->finish['assignedTo']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->task->form->finish['status']          = array('type' => 'string',   'required' => false, 'default' => 'done');
$config->task->form->finish['finishedDate']    = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->finish['lastEditedDate']  = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->finish['assignedDate']    = array('type' => 'string',   'required' => false, 'default' => $now);
$config->task->form->finish['finishedBy']      = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->finish['lastEditedBy']    = array('type' => 'string',   'required' => false, 'default' => $app->user->account);

$config->task->form->close = array();
$config->task->form->close['status']         = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->task->form->close['assignedTo']     = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->task->form->close['assignedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->close['closedBy']       = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->close['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->task->form->close['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->task->form->close['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->task->form->testTask->create = array();
$config->task->form->testTask->create['selectTestStory'] = array('type' => 'int',   'required' => false, 'default' => 0);
$config->task->form->testTask->create['testStory']       = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['testEstStarted']  = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['testDeadline']    = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['testAssignedTo']  = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['testPri']         = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['testEstimate']    = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['estStartedDitto'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->task->form->testTask->create['deadlineDitto']   = array('type' => 'array', 'required' => false, 'default' => array());

$config->task->form->recordWorkhour = array();
$config->task->form->recordWorkhour['date']     = array('type' => 'date',   'required' => true,  'default' => array());
$config->task->form->recordWorkhour['work']     = array('type' => 'string', 'required' => true,  'default' => array(), 'base' => true);
$config->task->form->recordWorkhour['consumed'] = array('type' => 'float',  'required' => true,  'default' => array());
$config->task->form->recordWorkhour['left']     = array('type' => 'float',  'required' => false, 'default' => array()); /* Set required to false as the required field can NOT be 0. */

$config->task->form->editEffort = array();
$config->task->form->editEffort['date']     = array('type' => 'date',   'required' => true,  'default' => '');
$config->task->form->editEffort['work']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->task->form->editEffort['consumed'] = array('type' => 'float',  'required' => true,  'default' => '');
$config->task->form->editEffort['left']     = array('type' => 'float',  'required' => true,  'default' => '');
