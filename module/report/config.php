<?php
/* Open daily reminder.*/
$config->report                          = new stdclass();
$config->report->dailyreminder           = new stdclass();
$config->report->dailyreminder->bug      = true;
$config->report->dailyreminder->task     = true;
$config->report->dailyreminder->todo     = true;
$config->report->dailyreminder->testTask = true;

$config->report->annualData['minMonth']     = 2;
$config->report->annualData['colors']       = array('#0075A9', '#22AC38', '#CAAC32', '#2B4D6D', '#0071a4', '#00a0e9', '#7ecef4');
$config->report->annualData['itemMinWidth'] = array(1 => 3, 2 => 5, 3 => 7, 4 => 9, 5 => 11);

$config->report->annualData['contributions']['task']     = array('opened' => 'create', 'assigned' => 'assign', 'finished' => 'finish', 'closed' => 'close', 'canceled' => 'cancel');
$config->report->annualData['contributions']['story']    = array('opened' => 'create', 'reviewed' => 'review', 'closed' => 'close', 'assigned' => 'assign');
$config->report->annualData['contributions']['bug']      = array('opened' => 'create', 'resolved' => 'resolve', 'closed' => 'close', 'assigned' => 'assign');
$config->report->annualData['contributions']['case']     = array('opened' => 'create');
$config->report->annualData['contributions']['testtask'] = array('closed' => 'close');
$config->report->annualData['contributions']['review']   = array('toaudit' => 'toAudit', 'audited' => 'audit');
$config->report->annualData['contributions']['cm']       = array('opened' => 'create');
$config->report->annualData['contributions']['doc']      = array('created' => 'create', 'edited' => 'edit');
$config->report->annualData['contributions']['issue']    = array('created' => 'create', 'closed' => 'close', 'assigned' => 'assign');
$config->report->annualData['contributions']['risk']     = array('created' => 'create', 'closed' => 'close', 'assigned' => 'assign');
$config->report->annualData['contributions']['qa']       = array('created' => 'create', 'closed' => 'close', 'assigned' => 'assign', 'resolved' => 'resolve');
$config->report->annualData['contributions']['feedback'] = array('created' => 'create', 'closed' => 'close', 'assigned' => 'assign', 'reviewed' => 'review');
$config->report->annualData['contributions']['ticket']   = array('created' => 'create', 'closed' => 'close', 'assigned' => 'assign', 'finished' => 'finish');
$config->report->annualData['contributions']['project']  = array('opened' => 'create', 'edited' => 'edit', 'closed' => 'close', 'deleted' => 'delete');

$config->report->annualData['radar']['product']['create']     = array('product');
$config->report->annualData['radar']['product']['edit']       = array('product');
$config->report->annualData['radar']['story']['create']       = array('product');
$config->report->annualData['radar']['story']['edit']         = array('product');
$config->report->annualData['radar']['story']['close']        = array('product');
$config->report->annualData['radar']['productplan']['create'] = array('product');
$config->report->annualData['radar']['release']['create']     = array('product');
$config->report->annualData['radar']['project']['create']     = array('execution');
$config->report->annualData['radar']['project']['edit']       = array('execution');
$config->report->annualData['radar']['project']['start']      = array('execution');
$config->report->annualData['radar']['project']['close']      = array('execution');
$config->report->annualData['radar']['execution']['create']   = array('execution');
$config->report->annualData['radar']['execution']['edit']     = array('execution');
$config->report->annualData['radar']['execution']['start']    = array('execution');
$config->report->annualData['radar']['execution']['close']    = array('execution');
$config->report->annualData['radar']['build']['create']       = array('execution');
$config->report->annualData['radar']['task']['create']        = array('execution', 'devel');
$config->report->annualData['radar']['task']['assign']        = array('execution', 'devel');
$config->report->annualData['radar']['task']['close']         = array('execution', 'devel');
$config->report->annualData['radar']['task']['activate']      = array('execution');
$config->report->annualData['radar']['repo']['svnCommit']     = array('devel');
$config->report->annualData['radar']['repo']['gitCommit']     = array('devel');
$config->report->annualData['radar']['bug']['resolve']        = array('devel');
$config->report->annualData['radar']['bug']['create']         = array('qa');
$config->report->annualData['radar']['bug']['activate']       = array('qa');
$config->report->annualData['radar']['bug']['close']          = array('qa');
$config->report->annualData['radar']['case']['create']        = array('qa');
$config->report->annualData['radar']['case']['run']           = array('qa');
$config->report->annualData['radar']['testtask']['create']    = array('qa');
$config->report->annualData['radar']['testtask']['edit']      = array('qa');

$config->report->annualData['monthAction']['story'] = array('opened', 'activated', 'closed', 'changed', 'fromfeedback', 'fromticket', 'deleted');
$config->report->annualData['monthAction']['task']  = array('opened', 'started', 'finished', 'paused', 'activated', 'canceled', 'closed', 'fromfeedback', 'deleted');
$config->report->annualData['monthAction']['bug']   = array('opened', 'bugconfirmed', 'activated', 'resolved', 'closed', 'fromfeedback', 'fromticket', 'deleted');
$config->report->annualData['monthAction']['case']  = array('opened', 'run', 'createBug', 'deleted');

$config->report->annualData['month']['story'] = array('opened' => 'create', 'activated' => 'activate', 'closed' => 'close', 'changed' => 'change', 'deleted' => 'delete');
$config->report->annualData['month']['task']  = array('opened' => 'create', 'started' => 'start', 'finished' => 'finish', 'paused' => 'pause', 'activated' => 'activate', 'canceled' => 'cancel', 'closed' => 'close', 'deleted' => 'delete');
$config->report->annualData['month']['bug']   = array('opened' => 'create', 'bugconfirmed' => 'confirm', 'activated' => 'activate', 'resolved' => 'resolve', 'closed' => 'close', 'deleted' => 'delete');
$config->report->annualData['month']['case']  = array('opened' => 'create', 'run' => 'run', 'createBug' => 'createBug', 'deleted' => 'delete');

$config->report->outputData['story']       = array('opened' => 'create', 'changed' => 'change', 'reviewed' => 'review', 'closed' => 'close');
$config->report->outputData['productplan'] = array('opened' => 'create');
$config->report->outputData['release']     = array('opened' => 'create', 'stoped' => 'stop', 'activated' => 'activate');
$config->report->outputData['execution']   = array('opened' => 'create', 'started' => 'start', 'delayed' => 'putoff', 'suspended' => 'suspend', 'closed' => 'close');
$config->report->outputData['task']        = array('opened' => 'create', 'assigned' => 'assign', 'finished' => 'finish', 'activated' => 'activate', 'closed' => 'close');
$config->report->outputData['bug']         = array('opened' => 'create', 'resolved' => 'resolve', 'activated' => 'activate', 'closed' => 'close');
$config->report->outputData['case']        = array('opened' => 'create', 'run' => 'run', 'createBug' => 'createBug');
