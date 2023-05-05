<?php
declare(strict_types=1);
helper::import(dirname(__FILE__) . 'config/form.php');
helper::import(dirname(dirname(dirname(__FILE__))) . '/lib/date/date.class.php');

$config->todo = new stdclass();
$config->todo->maxBatchCreate = 8;

if(!isset($config->todo->create)) $config->todo->create = new stdclass();
$config->todo->edit   = new stdclass();
$config->todo->dates  = new stdclass();
$config->todo->times  = new stdclass();
$config->todo->create->requiredFields = 'name';
$config->todo->edit->requiredFields   = 'name';
$config->todo->dates->end             = 15;
$config->todo->times->begin           = 6;
$config->todo->times->end             = 23;
$config->todo->times->delta           = 10;

$config->todo->editor = new stdclass();
$config->todo->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->todo->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->todo->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->todo->list = new stdclass();
$config->todo->list->exportFields            = 'id, account, date, begin, end, type, objectID, pri, name, desc, status, private';
$config->todo->list->customBatchCreateFields = 'type,pri,desc,beginAndEnd';
$config->todo->list->customBatchEditFields   = 'pri,beginAndEnd,status';

$config->todo->custom = new stdclass();
$config->todo->custom->batchCreateFields = 'type,pri,desc,beginAndEnd';
$config->todo->custom->batchEditFields   = 'pri,beginAndEnd,status';

$config->todo->moduleList = array('bug', 'task', 'story', 'testtask');

$config->todo->getUserObjectsMethod = array();
$config->todo->getUserObjectsMethod['bug']      = 'ajaxGetUserBugs';
$config->todo->getUserObjectsMethod['task']     = 'ajaxGetUserTasks';
$config->todo->getUserObjectsMethod['story']    = 'ajaxGetUserStories';
$config->todo->getUserObjectsMethod['testtask'] = 'ajaxGetUserTestTasks';

$config->todo->objectList = array();
$config->todo->objectList['bug']      = 'bugs';
$config->todo->objectList['task']     = 'tasks';
$config->todo->objectList['story']    = 'stories';
$config->todo->objectList['testtask'] = 'testtasks';

$config->todo->sessionUri = array();
$config->todo->sessionUri['bugList']      = 'qa';
$config->todo->sessionUri['taskList']     = 'execution';
$config->todo->sessionUri['storyList']    = 'product';
$config->todo->sessionUri['testtaskList'] = 'qa';

$config->todo->project = array();
$config->todo->project['task']        = TABLE_TASK;
$config->todo->project['bug']         = TABLE_BUG;
$config->todo->project['issue']       = TABLE_ISSUE;
$config->todo->project['risk']        = TABLE_RISK;
$config->todo->project['opportunity'] = TABLE_OPPORTUNITY;
$config->todo->project['review']      = TABLE_REVIEW;
$config->todo->project['testtask']    = TABLE_TESTTASK;

$config->todo->dateRange = array();
$config->todo->dateRange['all']             = array('begin' => '1970-01-01',  'end' => '2109-01-01');
$config->todo->dateRange['assignedtoother'] = array('begin' => '1970-01-01',  'end' => '2109-01-01');
$config->todo->dateRange['today']           = array('begin' => date::today(), 'end' => date::today());
$config->todo->dateRange['future']          = array('begin' => '2030-01-01',  'end' => '2030-01-01');
$config->todo->dateRange['before']          = array('begin' => '1970-01-01',  'end' => date::today());
$config->todo->dateRange['cycle']           = array('begin' => '', 'end' => '');
$config->todo->dateRange['yesterday']       = array('begin' => date::yesterday(), 'end' => date::yesterday());
$config->todo->dateRange['thisweek']        = array('begin' => date::getThisWeek()['begin'],   'end' => date::getThisWeek()['end']);
$config->todo->dateRange['lastweek']        = array('begin' => date::getLastWeek()['begin'],   'end' => date::getLastWeek()['end']);
$config->todo->dateRange['thismonth']       = array('begin' => date::getThisMonth()['begin'],  'end' => date::getThisMonth()['end']);
$config->todo->dateRange['lastmonth']       = array('begin' => date::getLastMonth()['begin'],  'end' => date::getLastMonth()['end']);
$config->todo->dateRange['thisseason']      = array('begin' => date::getThisSeason()['begin'], 'end' => date::getThisSeason()['end']);
$config->todo->dateRange['thisyear']        = array('begin' => date::getThisYear()['begin'],   'end' => date::getThisYear()['end']);
