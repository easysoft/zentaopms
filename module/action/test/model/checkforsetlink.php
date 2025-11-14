#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$project = zenData('project');
$project->project->range('0-4');
$project->parent->range('0-4');
$project->gen(5);

su('admin');

/**

title=测试 actionModel->checkForSetLink();
timeout=0
cid=14881

- 检查超级管理员 @1
- 传入父项目ID @0
- 没有执行权限 @0
- 没有项目权限 @0
- 动态没有关联产品 @1
- 没有产品权限 @0
- 有所有权限 @1

*/

global $tester, $app;
$actionModel = $tester->loadModel('action');

$app->user->admin = true;
$app->user->view  = new stdclass();
$app->user->view->products = '1,2,3,4';
$app->user->view->projects = '1,2,3,4';
$app->user->view->sprints  = '5,6,7,8';

$action = new stdclass();
$action->objectType = 'execution';
$action->objectID   = '3';
$action->project    = '1';
$action->execution  = '5';
$action->product    = ',1,';

$projectIdList = array('1', '2', '3', '4');

r($actionModel->checkForSetLink($action))                 && p() && e('1'); //检查超级管理员
r($actionModel->checkForSetLink($action, $projectIdList)) && p() && e('0'); //传入父项目ID

$app->user->admin   = false;
$action->objectType = 'task';
$action->execution  = '10';
r($actionModel->checkForSetLink($action)) && p() && e('0');   //没有执行权限

$action->execution  = '5';
$action->project    = '15';
r($actionModel->checkForSetLink($action)) && p() && e('0');   //没有项目权限

$action->project    = '1';
$action->product    = ',0,';
r($actionModel->checkForSetLink($action)) && p() && e('1');   //动态没有关联产品

$action->product = ',10,';
r($actionModel->checkForSetLink($action)) && p() && e('0');   //没有产品权限

$action->product = ',1,';
r($actionModel->checkForSetLink($action)) && p() && e('1');   //有所有权限