<?php
$config->todo = new stdclass();
$config->todo->batchCreate = 8;

$config->todo->create = new stdclass();
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
$config->todo->list->exportFields            = 'id, account, date, begin, end, type, assignedTo, idvalue, pri, name, desc, status, private';
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
