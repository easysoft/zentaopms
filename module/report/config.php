<?php
/* Open daily reminder.*/
$config->report                          = new stdclass();
$config->report->dailyreminder           = new stdclass();
$config->report->dailyreminder->bug      = true;
$config->report->dailyreminder->task     = true;
$config->report->dailyreminder->todo     = true;
$config->report->dailyreminder->testTask = true;

$config->report->annualData['minMonth'] = 2;

$config->report->annualData['po']['block1'] = array('title' => 'baseInfo', 'data' => array('logins', 'involvedProducts', 'createdPlans', 'createdStories'));
$config->report->annualData['po']['block2'] = array('title' => 'productOverview', 'data' => array());
$config->report->annualData['po']['block3'] = array('title' => '', 'data' => array('products'));
$config->report->annualData['po']['block4'] = array('title' => 'poData', 'data' => array('storyPri', 'storyStage'));
$config->report->annualData['po']['block5'] = array('title' => 'poStatistics', 'data' => array('storyMonth'));

$config->report->annualData['dev']['block1'] = array('title' => 'baseInfo', 'data' => array('logins', 'actions', 'efforts', 'consumed'));
$config->report->annualData['dev']['block2'] = array('title' => 'projectOverview', 'data' => array('doneProject', 'doingProject', 'suspendProject'));
$config->report->annualData['dev']['block3'] = array('title' => '', 'data' => array('projects'));
$config->report->annualData['dev']['block4'] = array('title' => 'devData', 'data' => array('finishedTaskPri', 'resolvedBugPri'));
$config->report->annualData['dev']['block5'] = array('title' => 'devStatistics', 'data' => array('taskMonth', 'effortMonth', 'bugMonth'));

$config->report->annualData['qa']['block1'] = array('title' => 'baseInfo', 'data' => array('logins', 'actions', 'foundBugs', 'createdCases'));
$config->report->annualData['qa']['block2'] = array('title' => 'qaOverview', 'data' => array());
$config->report->annualData['qa']['block3'] = array('title' => '', 'data' => array('projects'));
$config->report->annualData['qa']['block4'] = array('title' => 'qaData', 'data' => array('bugPri', 'casePri'));
$config->report->annualData['qa']['block5'] = array('title' => 'qaStatistics', 'data' => array('bugMonth', 'caseMonth'));
