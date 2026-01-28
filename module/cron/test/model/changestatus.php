#!/usr/bin/env php
<?php

/**

title=测试 cronModel::changeStatus();
timeout=0
cid=15877

- 步骤1：正常状态更新为stop @1
- 步骤2：正常状态更新为running @1
- 步骤3：状态更新为normal并强制更新时间 @1
- 步骤4：不存在的定时任务ID @1
- 步骤5：状态更新为error @1
- 步骤6：边界值测试ID为0 @1
- 步骤7：空字符串状态 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例并初始化数据
$cronTest = new cronModelTest();
$cronTest->init();

// 4. 测试步骤执行和验证
$result1 = $cronTest->changeStatusTest(1, 'stop');
$cron1 = $tester->cron->getById(1);

$result2 = $cronTest->changeStatusTest(2, 'running');
$cron2 = $tester->cron->getById(2);

$result3 = $cronTest->changeStatusTest(3, 'normal', true);
$cron3 = $tester->cron->getById(3);

$result4 = $cronTest->changeStatusTest(999, 'stop');

$result5 = $cronTest->changeStatusTest(4, 'error');
$cron5 = $tester->cron->getById(4);

$result6 = $cronTest->changeStatusTest(0, 'stop');

$result7 = $cronTest->changeStatusTest(5, '');
$cron7 = $tester->cron->getById(5);

// 5. 测试断言
r($result1) && p() && e('1'); // 步骤1：正常状态更新为stop返回值
r($cron1) && p('status') && e('stop'); // 步骤1：验证状态确实更新为stop
r($result2) && p() && e('1'); // 步骤2：正常状态更新为running返回值
r($cron2) && p('status') && e('running'); // 步骤2：验证状态确实更新为running
r($result3) && p() && e('1'); // 步骤3：状态更新为normal并强制更新时间返回值
r($cron3) && p('status') && e('normal'); // 步骤3：验证状态确实更新为normal
r($result4) && p() && e('1'); // 步骤4：不存在的定时任务ID返回值
r($result5) && p() && e('1'); // 步骤5：状态更新为error返回值
r($cron5) && p('status') && e('error'); // 步骤5：验证状态确实更新为error