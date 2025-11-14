#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(10);
zenData('story')->gen(10);
$project = zenData('project');
$project->project->range('0-1');
$project->parent->range('0-1');
$project->gen(2);

su('admin');

/**

title=测试 actionModel->setObjectLink();
timeout=0
cid=14932

- 检查超级管理员
 - 属性objectLabel @任务
 - 属性objectLink @/task-view-1.html
- 检查普通账号
 - 属性objectLabel @任务
 - 属性objectLink @/task-view-1.html
- 检查没有项目权限
 - 属性objectLabel @任务
 - 属性objectLink @~~
- 检查执行动态
 - 属性objectLabel @执行
 - 属性objectLink @~~
- 检查团队动态
 - 属性objectLabel @团队
 - 属性objectLink @/project-team-1.html
- 检查评审动态
 - 属性objectLabel @评审
 - 属性objectLink @/review-view-1.html
- 检查项目视图下Build动态
 - 属性objectLabel @Build
 - 属性objectLink @/projectbuild-view-1.html
- 检查运营界面下需求动态
 - 属性objectLabel @需求
 - 属性objectLink @/projectstory-view-1.html

*/

$action = new stdclass();
$action->id          = 1;
$action->objectType  = 'task';
$action->objectID    = 1;
$action->action      = 'opened';
$action->actor       = 'user1';
$action->date        = date('Y-m-d H:i:s');
$action->comment     = '';
$action->extra       = '';
$action->product     = ',1,';
$action->project     = 1;
$action->execution   = 3;
$action->objectLabel = '任务|task|view|taskID=%s';

$deptUsers = array('1' => 'admin', '2' => 'user1', '3' => 'user2', '4' => 'user3', '5' => 'user4', '6' => 'user5', '7' => 'user6', '8' => 'user7');

global $tester, $app, $config;
$config->webRoot     = '/';
$config->requestType = 'PATH_INFO';
$actionModel = $tester->loadModel('action');

r($actionModel->setObjectLink($action, $deptUsers, array(), '', array())) && p('objectLabel,objectLink') && e('任务,/task-view-1.html'); // 检查超级管理员

$action->objectLabel       = '任务|task|view|taskID=%s';
$app->user->view->projects = '1';
$app->user->view->products = '1';
$app->user->view->sprints  = '3';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array())) && p('objectLabel,objectLink') && e('任务,/task-view-1.html'); // 检查普通账号

$action->objectLabel       = '任务|task|view|taskID=%s';
$app->user->admin          = false;
$app->user->view->projects = '2';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array())) && p('objectLabel,objectLink') && e('任务,~~'); // 检查没有项目权限

$action->objectLabel = '执行|execution|view|id=%s';
$action->objectType  = 'execution';
$app->user->view->projects = '1';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array(1))) && p('objectLabel,objectLink') && e('执行,~~'); // 检查执行动态

$action->objectLabel = '团队';
$action->objectType  = 'team';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array(1))) && p('objectLabel,objectLink') && e('团队,/project-team-1.html'); // 检查团队动态

$action->objectLabel = '评审';
$action->objectType  = 'review';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array(1))) && p('objectLabel,objectLink') && e('评审,/review-view-1.html'); // 检查评审动态

$action->objectLabel = 'Build|build|view|id=%s';
$action->objectType  = 'build';
$app->tab            = 'project';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array())) && p('objectLabel,objectLink') && e('Build,/projectbuild-view-1.html'); // 检查项目视图下Build动态

$action->objectLabel = '需求|story|view|id=%s';
$action->objectType  = 'story';
$config->vision      = 'lite';
r($actionModel->setObjectLink($action, $deptUsers, array(), '', array())) && p('objectLabel,objectLink') && e('需求,/projectstory-view-1.html'); // 检查运营界面下需求动态