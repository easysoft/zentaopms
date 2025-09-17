#!/usr/bin/env php
<?php

/**

title=测试 systemZen::getMemUsage();
timeout=0
cid=0

- 步骤1：内存使用率为0
 - 属性color @gray
 - 属性rate @0
- 步骤2：内存使用率30%
 - 属性color @var(--color-secondary-500)
 - 属性rate @30
- 步骤3：内存使用率60%
 - 属性color @var(--color-warning-500)
 - 属性rate @60
- 步骤4：内存使用率85%
 - 属性color @var(--color-important-500)
 - 属性rate @85
- 步骤5：内存使用率95%
 - 属性color @var(--color-danger-500)
 - 属性rate @95

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$systemTest = new systemTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：内存使用率为0的情况
$metrics1 = new stdClass();
$metrics1->rate = 0;
$metrics1->usage = 0;
$metrics1->capacity = 1024;
r($systemTest->getMemUsageTest($metrics1)) && p('color,rate') && e('gray,0'); // 步骤1：内存使用率为0

// 步骤2：内存使用率为30%的正常使用情况
$metrics2 = new stdClass();
$metrics2->rate = 30;
$metrics2->usage = 307200; // 300MB
$metrics2->capacity = 1024000; // 1000MB
r($systemTest->getMemUsageTest($metrics2)) && p('color,rate') && e('var(--color-secondary-500),30'); // 步骤2：内存使用率30%

// 步骤3：内存使用率为60%的中等使用情况
$metrics3 = new stdClass();
$metrics3->rate = 60;
$metrics3->usage = 614400; // 600MB
$metrics3->capacity = 1024000; // 1000MB
r($systemTest->getMemUsageTest($metrics3)) && p('color,rate') && e('var(--color-warning-500),60'); // 步骤3：内存使用率60%

// 步骤4：内存使用率为85%的高使用率情况
$metrics4 = new stdClass();
$metrics4->rate = 85;
$metrics4->usage = 870400; // 850MB
$metrics4->capacity = 1024000; // 1000MB
r($systemTest->getMemUsageTest($metrics4)) && p('color,rate') && e('var(--color-important-500),85'); // 步骤4：内存使用率85%

// 步骤5：内存使用率为95%的危险使用率情况
$metrics5 = new stdClass();
$metrics5->rate = 95;
$metrics5->usage = 972800; // 950MB
$metrics5->capacity = 1024000; // 1000MB
r($systemTest->getMemUsageTest($metrics5)) && p('color,rate') && e('var(--color-danger-500),95'); // 步骤5：内存使用率95%