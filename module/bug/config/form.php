<?php
declare(strict_types=1);
global $lang;

$config->bug->form = new stdclass();

$config->bug->form->create = array();
$config->bug->form->create['title']       = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->bug->form->create['openedBuild'] = array('required' => true, 'type' => 'array',  'filter' => 'join');

$config->bug->form->create['product']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['branch']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['module']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['project']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['execution']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['assignedTo']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['deadline']    = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->create['feedbackBy']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['notifyEmail'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['type']        = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->create['os']       = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['browser']  = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['color']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['severity'] = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->create['pri']      = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->create['steps']    = array('required' => false, 'type' => 'string', 'default' => $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect);

$config->bug->form->create['story']       = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['task']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['oldTaskID']   = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['case']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['caseVersion'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['result']      = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['testtask']    = array('required' => false, 'type' => 'int', 'default' => 0);

$config->bug->form->create['mailto']   = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->create['keywords'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['status']   = array('required' => false, 'type' => 'string', 'default' => 'active');
$config->bug->form->create['issueKey'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->create['uid']      = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->edit = array();
$config->bug->form->edit['title']          = array('required' => true,  'type' => 'string', 'filter'  => 'trim');
$config->bug->form->edit['openedBuild']    = array('required' => true,  'type' => 'array',  'filter'  => 'join');
$config->bug->form->edit['product']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['branch']         = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['project']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['execution']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['plan']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['module']         = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['story']          = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['task']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['case']           = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['testtask']       = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['duplicateBug']   = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->edit['severity']       = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->edit['pri']            = array('required' => false, 'type' => 'int',    'default' => 3);
$config->bug->form->edit['type']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['status']         = array('required' => false, 'type' => 'string', 'default' => 'active');
$config->bug->form->edit['keywords']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['steps']          = array('required' => false, 'type' => 'string', 'default' => $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect);
$config->bug->form->edit['resolution']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['resolvedBuild']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['feedbackBy']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['resolvedBy']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['closedBy']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['notifyEmail']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['uid']            = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->edit['os']             = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['browser']        = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['linkBug']        = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['mailto']         = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
$config->bug->form->edit['deadline']       = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['resolvedDate']   = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['closedDate']     = array('required' => false, 'type' => 'date',   'default' => null);
$config->bug->form->edit['lastEditedDate'] = array('required' => false, 'type' => 'date',   'default' => helper::now());

global $app;
$config->bug->form->close = array();
$config->bug->form->close['status']         = array('required' => false, 'type' => 'string', 'default' => 'closed');
$config->bug->form->close['confirmed']      = array('required' => false, 'type' => 'int',    'default' => 1);
$config->bug->form->close['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->close['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['closedBy']       = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->close['closedDate']     = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->close['comment']        = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->form->assignTo = array();
$config->bug->form->assignTo['assignedTo']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->form->assignTo['assignedDate']   = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->assignTo['lastEditedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->bug->form->assignTo['lastEditedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
$config->bug->form->assignTo['mailto']         = array('required' => false, 'type' => 'array',  'default' => array(''), 'filter' => 'join');
