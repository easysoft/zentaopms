#!/usr/bin/env php
<?php

/**

title=测试 cronModel::restartCron();
timeout=0
cid=15886

- 步骤1：正常执行ID属性configAfter @2001
- 步骤2：字符串执行ID属性configAfter @3002
- 步骤3：空执行ID属性configAfter @~~
- 步骤4：负数执行ID属性configAfter @-100
- 步骤5：零执行ID属性configAfter @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cron.unittest.class.php';

// 2. zendata数据准备
$oldWeekDate = date("Y-m-d H:i:s", strtotime("-1 week"));
$tester->dbh->exec("INSERT IGNORE INTO zt_config (owner, module, section, `key`, value) VALUES ('system', 'cron', 'scheduler', 'execId', '1000')");
$tester->dbh->exec("INSERT IGNORE INTO zt_queue (cron, type, command, status, createdDate) VALUES (1, 'cron', 'test command', 'wait', '$oldWeekDate')");
$tester->dbh->exec("INSERT IGNORE INTO zt_queue (cron, type, command, status, createdDate) VALUES (2, 'cron', 'test command', 'done', '$oldWeekDate')");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$cronTest = new cronTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($cronTest->restartCronTest(2001)) && p('configAfter') && e('2001'); // 步骤1：正常执行ID
r($cronTest->restartCronTest('3002')) && p('configAfter') && e('3002'); // 步骤2：字符串执行ID
r($cronTest->restartCronTest('')) && p('configAfter') && e('~~'); // 步骤3：空执行ID
r($cronTest->restartCronTest(-100)) && p('configAfter') && e('-100'); // 步骤4：负数执行ID
r($cronTest->restartCronTest(0)) && p('configAfter') && e('0'); // 步骤5：零执行ID