#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');
zenData('actionrecent')->gen(10);

/**

title=测试 actionModel->cleanActions();
timeout=0
cid=14882

- 第一次执行，检查返回结果 @1
- 清空缓存动态，检查条目数 @8
- 重复清空缓存动态，检查返回结果 @1
- 重复清空缓存动态，检查条目数 @8
- 第三次执行，检查返回结果 @1
- 第三次执行，检查条目数 @5

*/

global $tester, $app;
$actionModel = $tester->loadModel('action');
$actionModel->dao->update(TABLE_ACTIONRECENT)->set('date')->eq(date('Y-m-d H:i:s'))->exec();
$actionModel->dao->update(TABLE_ACTIONRECENT)->set('date')->eq(date('Y-m-d H:i:s', strtotime('-1 month') - 24 * 3600))->where('id')->le('2')->exec();

unset($app->config->global->cleanActionsDate);
$result = $actionModel->cleanActions();
$count  = $actionModel->dao->select('count(*) as count')->from(TABLE_ACTIONRECENT)->fetch('count');
r($result) && p() && e('1'); //第一次执行，检查返回结果
r($count)  && p() && e('8'); //清空缓存动态，检查条目数

$app->config->global->cleanActionsDate = date('Y-m-d H:i:s');
$result = $actionModel->cleanActions();
$count = $actionModel->dao->select('count(*) as count')->from(TABLE_ACTIONRECENT)->fetch('count');
r($result) && p() && e('1'); //重复清空缓存动态，检查返回结果
r($count)  && p() && e('8'); //重复清空缓存动态，检查条目数

unset($config->global->cleanActionsDate);
$actionModel->dao->update(TABLE_ACTIONRECENT)->set('date')->eq(date('Y-m-d H:i:s', strtotime('-1 month') - 24 * 3600))->where('id')->le('5')->exec();
$result = $actionModel->cleanActions();
$count  = $actionModel->dao->select('count(*) as count')->from(TABLE_ACTIONRECENT)->fetch('count');
r($result) && p() && e('1'); //第三次执行，检查返回结果
r($count)  && p() && e('5'); //第三次执行，检查条目数