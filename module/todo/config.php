<?php
declare(strict_types=1);

$config->todo = new stdclass();
$config->todo->batchCreateNumber = 8;
$config->todo->defaultPri        = 3;

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
$config->todo->list->exportFields            = 'id, account, date, begin, end, type, assignedTo, objectID, pri, name, desc, status, private';
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
$config->todo->sessionUri['todoList']     = 'my';
$config->todo->sessionUri['taskList']     = 'execution';
$config->todo->sessionUri['storyList']    = 'product';
$config->todo->sessionUri['testtaskList'] = 'qa';

$config->todo->project = array();
$config->todo->project['task']        = TABLE_TASK;
$config->todo->project['bug']         = TABLE_BUG;
$config->todo->project['testtask']    = TABLE_TESTTASK;
if($this->config->edition == 'max')
{
    $config->todo->project['issue']       = TABLE_ISSUE;
    $config->todo->project['risk']        = TABLE_RISK;
    $config->todo->project['opportunity'] = TABLE_OPPORTUNITY;
    $config->todo->project['review']      = TABLE_REVIEW;
}
helper::import(dirname(__FILE__) . 'config/form.php');
helper::import(dirname(__FILE__) . 'config/toolbar.php');

$config->todo->related = array();
$config->todo->related['story']['title']   = array('legendSpec', 'legendVerify');
$config->todo->related['story']['content'] = array('spec', 'verify');
$config->todo->related['task']['title']    = array('legendDesc');
$config->todo->related['task']['content']  = array('desc');
$config->todo->related['bug']['title']     = array('legendSteps');
$config->todo->related['bug']['content']   = array('steps');
