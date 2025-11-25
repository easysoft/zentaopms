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
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

$effort1 = new stdclass();
$effort1->account = 'user1';
$effort1->project = 1;
$effort1->execution = 1;

$emptyEffort = new stdclass();

$effort3 = new stdclass();
$effort3->account = 'admin';

$effort4 = new stdclass();
$effort4->account = 'user2';
$effort4->project = 2;
$effort4->execution = 2;

$effort5 = new stdclass();
$effort5->account = 'user1';
$effort5->project = 1;
$effort5->execution = 1;

r($commonTest->canOperateEffortTest($effort1)) && p() && e('1'); // 步骤1：管理员用户可以操作任何日志
r($commonTest->canOperateEffortTest($emptyEffort)) && p() && e('1'); // 步骤2：空effort对象的处理
r($commonTest->canOperateEffortTest($effort3)) && p() && e('1'); // 步骤3：用户可以操作自己的日志
r($commonTest->canOperateEffortTest($effort4)) && p() && e('0'); // 步骤4：用户不能操作他人的日志（无权限）
r($commonTest->canOperateEffortTest($effort5)) && p() && e('1'); // 步骤5：项目负责人可以操作团队成员日志