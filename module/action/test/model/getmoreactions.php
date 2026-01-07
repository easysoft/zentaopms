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

title=测试 actionModel->getMoreActions();
timeout=0
cid=14904

- 测试传入空SESSION @0
- 测试传入条件 actor='admin'。 @0
- 测试传入条件 actor='admin' AND t2.product='1'。 @0
- 测试传入条件 1=1。 @2
- 测试传入条件 actor='admin'。 @1
- 测试传入条件 actor='admin' AND t2.product='1'。 @0

*/

global $tester;
$actionModel = $tester->loadModel('action');

$actionID = 3;

$_SESSION['actionOrderBy']        = '`date` desc';
$_SESSION['actionQueryCondition'] = '';
r(count($actionModel->getMoreActions($actionID))) && p() && e('0');  // 测试传入空SESSION

$_SESSION['actionQueryCondition'] = "actor='admin'";
r(count($actionModel->getMoreActions($actionID))) && p() && e('0');  // 测试传入条件 actor='admin'。

$_SESSION['actionQueryCondition'] = "actor='admin' AND t2.product='1'";
r(count($actionModel->getMoreActions($actionID))) && p() && e('0');  // 测试传入条件 actor='admin' AND t2.product='1'。

$actionModel->dao->update(TABLE_ACTION)->set("`date` = concat('2025-05-19 15:15:5', id)")->exec();
$_SESSION['actionQueryCondition'] = "1=1";
r(count($actionModel->getMoreActions($actionID))) && p() && e('2');  // 测试传入条件 1=1。

$_SESSION['actionQueryCondition'] = "actor='admin'";
r(count($actionModel->getMoreActions($actionID))) && p() && e('1');  // 测试传入条件 actor='admin'。

$_SESSION['actionQueryCondition'] = "actor='admin' AND t2.product='1'";
r(count($actionModel->getMoreActions($actionID))) && p() && e('0');  // 测试传入条件 actor='admin' AND t2.product='1'。
