<?php
declare(strict_types=1);

$config->bug->createform = array();
$config->bug->createform['title']       = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->bug->createform['openedBuild'] = array('required' => true, 'type' => 'array', 'filter' => 'join');

$config->bug->createform['product']     = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['branch']      = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['module']      = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['project']     = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['execution']   = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['assignedTo']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['deadline']    = array('required' => false, 'type' => 'date', 'default' => '');
$config->bug->createform['feedbackBy']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['notifyEmail'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['type']        = array('required' => false, 'type' => 'string', 'default' => '');

$config->bug->createform['os']       = array('required' => false, 'type' => 'array', 'default' => array(''), 'filter' => 'join');
$config->bug->createform['browser']  = array('required' => false, 'type' => 'array', 'default' => array(''), 'filter' => 'join');
$config->bug->createform['color']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['severity'] = array('required' => false, 'type' => 'int', 'default' => 3);
$config->bug->createform['pri']      = array('required' => false, 'type' => 'int', 'default' => 3);
$config->bug->createform['steps']    = array('required' => false, 'type' => 'string', 'default' => '<p>[步骤]</p><br/><p>[结果]</p><br/><p>[期望]</p><br/>');

$config->bug->createform['story']       = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['task']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['oldTaskID']   = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['case']        = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['caseVersion'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['result']      = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->createform['testtask']    = array('required' => false, 'type' => 'int', 'default' => 0);

$config->bug->createform['mailto']    = array('required' => false, 'type' => 'array', 'default' => array(''), 'filter' => 'join');
$config->bug->createform['keywords']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['status']    = array('required' => false, 'type' => 'string', 'default' => 'active');
$config->bug->createform['issueKey']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->bug->createform['uid']       = array('required' => false, 'type' => 'string',  'default' => '');
