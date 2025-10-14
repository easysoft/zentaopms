#!/usr/bin/env php
<?php

/**

title=测试 systemZen::getCpuUsage();
timeout=0
cid=0

- 执行systemTest模块的getCpuUsageTest方法，参数是$metrics0
 - 属性color @gray
 - 属性rate @0
- 执行systemTest模块的getCpuUsageTest方法，参数是$metrics25
 - 属性color @var(--color-secondary-500)
 - 属性rate @25
- 执行systemTest模块的getCpuUsageTest方法，参数是$metrics60
 - 属性color @var(--color-warning-500)
 - 属性rate @60
- 执行systemTest模块的getCpuUsageTest方法，参数是$metrics80
 - 属性color @var(--color-important-500)
 - 属性rate @80
- 执行systemTest模块的getCpuUsageTest方法，参数是$metrics95
 - 属性color @var(--color-danger-500)
 - 属性rate @95

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$systemTest = new systemTest();

// 4. 创建测试数据对象
// 测试步骤1：CPU使用率为0%
$metrics0 = new stdClass();
$metrics0->rate = 0;
$metrics0->usage = 0;
$metrics0->capacity = 100;

// 测试步骤2：CPU使用率为25%
$metrics25 = new stdClass();
$metrics25->rate = 25;
$metrics25->usage = 25;
$metrics25->capacity = 100;

// 测试步骤3：CPU使用率为60%
$metrics60 = new stdClass();
$metrics60->rate = 60;
$metrics60->usage = 60;
$metrics60->capacity = 100;

// 测试步骤4：CPU使用率为80%
$metrics80 = new stdClass();
$metrics80->rate = 80;
$metrics80->usage = 80;
$metrics80->capacity = 100;

// 测试步骤5：CPU使用率为95%
$metrics95 = new stdClass();
$metrics95->rate = 95;
$metrics95->usage = 95;
$metrics95->capacity = 100;

// 5. 执行测试步骤
r($systemTest->getCpuUsageTest($metrics0)) && p('color,rate') && e('gray,0');
r($systemTest->getCpuUsageTest($metrics25)) && p('color,rate') && e('var(--color-secondary-500),25');
r($systemTest->getCpuUsageTest($metrics60)) && p('color,rate') && e('var(--color-warning-500),60');
r($systemTest->getCpuUsageTest($metrics80)) && p('color,rate') && e('var(--color-important-500),80');
r($systemTest->getCpuUsageTest($metrics95)) && p('color,rate') && e('var(--color-danger-500),95');