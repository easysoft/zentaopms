#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

zenData('user')->gen(30);
zenData('project')->gen(10);
zenData('dept')->gen(10);

su('admin');

/**

title=测试 commonModel::canOperateEffort();
timeout=0
cid=1

- 管理员用户可以操作任何日志 @1
- 空effort对象的处理 @1
- 用户可以操作自己的日志 @1
- 用户不能操作他人的日志（无权限） @0
- 项目负责人可以操作团队成员日志 @1

*/

$commonTest = new commonTest();

// 测试数据准备
$effort1 = new stdclass();
$effort1->account = 'user1';
$effort1->project = 1;
$effort1->execution = 1;

$emptyEffort = new stdclass();

$effort3 = new stdclass();
$effort3->account = 'admin';

$effort4 = new stdclass();
$effort4->account = 'user2';
$effort4->project = 5;
$effort4->execution = 5;

$effort5 = new stdclass();
$effort5->account = 'user3';
$effort5->project = 2;
$effort5->execution = 0;

r($commonTest->canOperateEffortTest($effort1)) && p() && e('1');     // 管理员用户可以操作任何日志
r($commonTest->canOperateEffortTest($emptyEffort)) && p() && e('1');  // 空effort对象的处理

// 切换到普通用户测试
su('admin');
r($commonTest->canOperateEffortTest($effort3)) && p() && e('1');     // 用户可以操作自己的日志

su('user1');
r($commonTest->canOperateEffortTest($effort4)) && p() && e('0');     // 用户不能操作他人的日志（无权限）
r($commonTest->canOperateEffortTest($effort5)) && p() && e('1');     // 项目负责人可以操作团队成员日志