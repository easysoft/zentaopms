#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('action')->gen(0);
zdTable('task')->config('task', true)->gen(10);

/**

title=taskModel->updateChildrenByParent();
timeout=0
cid=1

*/

$parentIdList = array(1, 6, 7);
$actionList   = array('Started', 'Activated', 'Closed');
$commentList  = array('', '这是一条备注');

$taskTester = new taskTest();

r($taskTester->updateChildrenByParentTest($parentIdList[0], $actionList[0], $commentList[0])) && p() && e('0'); // 测试开始普通任务
r($taskTester->updateChildrenByParentTest($parentIdList[0], $actionList[1], $commentList[0])) && p() && e('0'); // 测试激活普通任务
r($taskTester->updateChildrenByParentTest($parentIdList[0], $actionList[2], $commentList[0])) && p() && e('0'); // 测试关闭普通任务
r($taskTester->updateChildrenByParentTest($parentIdList[0], $actionList[0], $commentList[1])) && p() && e('0'); // 测试开始普通任务并留备注

r($taskTester->updateChildrenByParentTest($parentIdList[1], $actionList[0], $commentList[0])) && p('action') && e('started');   // 测试开始父任务
r($taskTester->updateChildrenByParentTest($parentIdList[1], $actionList[1], $commentList[0])) && p('action') && e('activated'); // 测试激活父任务
r($taskTester->updateChildrenByParentTest($parentIdList[1], $actionList[2], $commentList[0])) && p('action') && e('closed');    // 测试关闭父任务
r($taskTester->updateChildrenByParentTest($parentIdList[1], $actionList[0], $commentList[1])) && p('action') && e('started');   // 测试开始父任务并留备注

r($taskTester->updateChildrenByParentTest($parentIdList[2], $actionList[0], $commentList[0])) && p() && e('0'); // 测试开始子任务
r($taskTester->updateChildrenByParentTest($parentIdList[2], $actionList[1], $commentList[0])) && p() && e('0'); // 测试激活子任务
r($taskTester->updateChildrenByParentTest($parentIdList[2], $actionList[2], $commentList[0])) && p() && e('0'); // 测试关闭子任务
r($taskTester->updateChildrenByParentTest($parentIdList[2], $actionList[0], $commentList[1])) && p() && e('0'); // 测试开始子任务并留备注
