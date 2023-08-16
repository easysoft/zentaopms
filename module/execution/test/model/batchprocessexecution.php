#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution', true)->gen(30);
zdTable('product')->config('product', true)->gen(30);
zdTable('projectproduct')->config('projectproduct', true)->gen(60);

/**

title=测试 executionModel->batchProcessExecution();
timeout=0
cid=1

*/

global $tester;
$executionModel = $tester->loadModel('execution');

$executions = $executionModel->fetchExecutionList(60, 'undone');
$projectID  = 60;
$productID  = 5;
$paramList  = array('skipParent', 'hasParentName');

r($executionModel->batchProcessExecution(array()))                                                  && p()           && e('0');                              // 测试空数据
r($executionModel->batchProcessExecution($executions))                                              && p('117:name') && e('阶段21');                         // 测试处理执行
r($executionModel->batchProcessExecution($executions, $projectID))                                  && p('117:name') && e('阶段21');                         // 测试处理瀑布项目2的执行
r($executionModel->batchProcessExecution($executions, $projectID, $productID, true))                && p('117:name') && e('阶段21');                         // 测试处理产品下的执行下
r($executionModel->batchProcessExecution($executions, $projectID, 0         , true))                && p('117:name') && e('阶段21');                         // 测试处理执行下的任务
r($executionModel->batchProcessExecution($executions, $projectID, 0         , true, $paramList[0])) && p('117:name') && e('瀑布项目2 / 阶段21');             // 测试去掉执行中的父阶段
r($executionModel->batchProcessExecution($executions, $projectID, 0         , true, $paramList[1])) && p('117:name') && e('瀑布项目2 / 阶段21');             // 处理执行中的父阶段的名称
r($executionModel->batchProcessExecution($executions, $projectID, $productID, true))                && p('117:name') && e('瀑布项目2 / 阶段21');             // 测试处理执行下的任务
r($executionModel->batchProcessExecution($executions, $projectID, $productID, true, $paramList[0])) && p('117:name') && e('瀑布项目2 / 瀑布项目2 / 阶段21'); // 测试去掉执行中的父阶段
r($executionModel->batchProcessExecution($executions, $projectID, $productID, true, $paramList[1])) && p('117:name') && e('瀑布项目2 / 瀑布项目2 / 阶段21'); // 处理执行中的父阶段的名称
