#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';
su('admin');

zenData('action')->loadYaml('action')->gen(6);
zenData('actionrecent')->gen(5);
zenData('actionproduct')->gen(0);

/**

title=测试 actionModel->hasMoreAction();
timeout=0
cid=14914

- 测试传入空SESSION @0
- 测试传入条件 actor='admin'。 @0
- 检查SQL。 @1
- 测试传入条件 actor='admin' AND t2.product='1'。 @0
- 检查SQL。 @1
- 测试传入条件 1=1。 @1

*/

global $tester;
$actionModel = $tester->loadModel('action');

$lastAction = new stdclass();
$lastAction->id = 3;
$lastAction->originalDate = '2025-05-19 15:15:53';

$_SESSION['actionOrderBy']        = '`date` desc';
$_SESSION['actionQueryCondition'] = '';
r($actionModel->hasMoreAction($lastAction)) && p() && e('0');  // 测试传入空SESSION

$_SESSION['actionQueryCondition'] = "actor='admin'";
r($actionModel->hasMoreAction($lastAction)) && p() && e('0');  // 测试传入条件 actor='admin'。

$sql = $actionModel->dao->get();
r(strpos($sql, "LEFT JOIN `zt_actionproduct` AS t2  ON action.id=t2.action") === false) && p() && e('1');  // 检查SQL。

$_SESSION['actionQueryCondition'] = "actor='admin' AND t2.product='1';";
r($actionModel->hasMoreAction($lastAction)) && p() && e('0');  // 测试传入条件 actor='admin' AND t2.product='1'。

$sql = $actionModel->dao->get();
r(strpos($sql, "LEFT JOIN `zt_actionproduct` AS t2  ON action.id=t2.action") !== false) && p() && e('1');  // 检查SQL。

$actionModel->dao->update(TABLE_ACTION)->set("`date` = concat('2025-05-19 15:15:5', id)")->exec();
$_SESSION['actionQueryCondition'] = "1=1";
r($actionModel->hasMoreAction($lastAction)) && p() && e('1');  // 测试传入条件 1=1。