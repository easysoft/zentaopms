#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';
su('admin');

$action = zenData('action');
$action->date->range('(-1h)-(+1w):1D')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$action->gen(6);

zenData('actionrecent')->gen(5);
zenData('actionproduct')->gen(0);

/**

title=测试 actionModel->getDynamicCount();
timeout=0
cid=14899

- 测试传入空SESSION @0
- 测试传入条件 1=1。 @6
- 测试传入条件 actor='admin'。 @2
- 检查SQL。 @1
- 测试传入条件 actor='admin' AND t2.product='1'。 @0
- 检查SQL。 @1
- 测试传入条件 1=1。 @0
- 检查SQL。 @1

*/

global $tester;
$actionModel = $tester->loadModel('action');
$actionModel->getDynamicCount('all');

$_SESSION['actionQueryCondition'] = '';
r($actionModel->getDynamicCount('all')) && p() && e('0');  // 测试传入空SESSION

$_SESSION['actionQueryCondition'] = "1=1";
r($actionModel->getDynamicCount('all')) && p() && e('6');  // 测试传入条件 1=1。

$_SESSION['actionQueryCondition'] = "actor='admin'";
r($actionModel->getDynamicCount('all')) && p() && e('2');  // 测试传入条件 actor='admin'。

$sql = $actionModel->dao->get();
r(strpos($sql, "LEFT JOIN `zt_actionproduct` AS t2  ON action.id=t2.action") === false) && p() && e('1');  // 检查SQL。

$_SESSION['actionQueryCondition'] = "actor='admin' AND t2.product='1'";
r($actionModel->getDynamicCount('all')) && p() && e('0');  // 测试传入条件 actor='admin' AND t2.product='1'。

$sql = $actionModel->dao->get();
r(strpos($sql, "LEFT JOIN `zt_actionproduct` AS t2  ON action.id=t2.action") !== false) && p() && e('1');  // 检查SQL。

$_SESSION['actionQueryCondition'] = "1=1";
r($actionModel->getDynamicCount('today')) && p() && e('0');  // 测试传入条件 1=1。

$sql = $actionModel->dao->get();
r(strpos($sql, "SELECT action.id FROM `zt_actionrecent` AS action") !== false) && p() && e('1');  // 检查SQL。
