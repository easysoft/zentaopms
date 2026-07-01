#!/usr/bin/env php
<?php

/**

title=测试 commonModel::canOperateEffort();
timeout=0
cid=15651

- 步骤1：管理员用户可以操作任何日志 @1
- 步骤2：空effort对象的处理 @1
- 步骤3：用户可以操作自己的日志 @1
- 步骤4：用户不能操作他人的日志（无权限） @0
- 步骤5：项目负责人可以操作团队成员日志 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester, $app;

zenData('company')->gen(1);
zenData('user')->gen(3);
zenData('dept')->gen(2);
zenData('project')->gen(2);
$tester->dao->update(TABLE_PROJECT)->set('PM')->eq('user1')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_PROJECT)->set('PM')->eq('admin')->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,')->exec();
$tester->dao->update(TABLE_USER)->set('dept')->eq(1)->where('account')->eq('user1')->exec();
$tester->dao->update(TABLE_USER)->set('dept')->eq(2)->where('account')->eq('user2')->exec();
$tester->dao->update(TABLE_DEPT)->set('path')->eq(',1,')->set('grade')->eq(1)->set('manager')->eq('admin')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_DEPT)->set('parent')->eq(1)->set('path')->eq(',1,2,')->set('grade')->eq(2)->set('manager')->eq('admin')->where('id')->eq(2)->exec();
$app->company->admins = ',admin,';

$commonTest = new commonModelTest();

$adminEffort = new stdclass();
$adminEffort->account = 'user2';
$adminEffort->project = 2;

$emptyEffort = new stdclass();

$selfEffort = new stdclass();
$selfEffort->account = 'user1';

$otherEffort = new stdclass();
$otherEffort->account = 'user2';

$pmEffort = new stdclass();
$pmEffort->account = 'user2';
$pmEffort->project = 1;

su('admin', false);
$app->user->admin = true;
r($commonTest->canOperateEffortTest($adminEffort)) && p() && e('1'); // 步骤1：管理员用户可以操作任何日志
r($commonTest->canOperateEffortTest($emptyEffort)) && p() && e('1'); // 步骤2：空effort对象的处理

su('user1', false);
$app->user->admin = false;
r($commonTest->canOperateEffortTest($selfEffort)) && p() && e('1'); // 步骤3：用户可以操作自己的日志
r($commonTest->canOperateEffortTest($otherEffort)) && p() && e('0'); // 步骤4：用户不能操作他人的日志（无权限）
r($commonTest->canOperateEffortTest($pmEffort)) && p() && e('1'); // 步骤5：项目负责人可以操作团队成员日志
