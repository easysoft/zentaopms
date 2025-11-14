#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
zenData('user')->gen(10);
su('admin');

/**

title=测试 commonModel::checkPrivForOperateAction();
timeout=0
cid=15662

- 测试研发需求创建日志按钮权限 @1
- 测试任务开始按钮权限 @1
- 测试确认BUG按钮权限 @1
- 测试执行用例按钮权限 @0
- 测试没有链接的情况 @0

*/
zenData('task')->gen(1);
zenData('bug')->gen(1);
zenData('case')->gen(1);

$storyData = zenData('story');
$storyData->type->range('story,requirement,epic');
$storyData->gen(3);

$storyAction = array();
$storyAction['url'] = array();
$storyAction['url']['module'] = 'effort';
$storyAction['url']['method'] = 'createForObject';
$storyAction['url']['params'] = 'type=story&storyID=1';

$taskAction = array();
$taskAction['url'] = array();
$taskAction['url']['module'] = 'task';
$taskAction['url']['method'] = 'start';
$taskAction['url']['params'] = 'taskID=1';

$bugAction = array();
$bugAction['url'] = array();
$bugAction['url']['module'] = 'bug';
$bugAction['url']['method'] = 'confirm';
$bugAction['url']['params'] = 'bugID=1';

$caseAction = array();
$caseAction['url'] = array();
$caseAction['url']['module'] = 'testtask';
$caseAction['url']['method'] = 'runCase';
$caseAction['url']['params'] = 'runID=0&caseID=1';

$commonModel = new commonTest();
r($commonModel->checkPrivForOperateActionTest('story',    'createeffort', $storyAction)) && p() && e('1'); //测试研发需求创建日志按钮权限
r($commonModel->checkPrivForOperateActionTest('task',     'start',        $taskAction))  && p() && e('1'); //测试任务开始按钮权限
r($commonModel->checkPrivForOperateActionTest('bug',      'confirm',      $bugAction))   && p() && e('1'); //测试确认BUG按钮权限
r($commonModel->checkPrivForOperateActionTest('testcase', 'runCase',      $caseAction))  && p() && e('0'); //测试执行用例按钮权限
r($commonModel->checkPrivForOperateActionTest('bug',      'create',       array()))      && p() && e('0'); //测试没有链接的情况
