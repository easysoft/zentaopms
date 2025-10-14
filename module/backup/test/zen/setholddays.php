#!/usr/bin/env php
<?php

/**

title=测试 backupZen::setHoldDays();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：空值验证属性holdDays @『保留天数』不能为空。
- 步骤3：非数字验证属性holdDays @『保留天数』应当是正整数。
- 步骤4：负数验证属性holdDays @『保留天数』应当是正整数。
- 步骤5：零值验证属性holdDays @『保留天数』应当是正整数。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常设置保留天数
$data1 = new stdClass();
$data1->holdDays = 30;
r($backupTest->setHoldDaysTest($data1)) && p() && e('1'); // 步骤1：正常情况

// 步骤2：空值验证
$data2 = new stdClass();
$data2->holdDays = '';
r($backupTest->setHoldDaysTest($data2)) && p('holdDays') && e('『保留天数』不能为空。'); // 步骤2：空值验证

// 步骤3：非数字验证
$data3 = new stdClass();
$data3->holdDays = 'abc';
r($backupTest->setHoldDaysTest($data3)) && p('holdDays') && e('『保留天数』应当是正整数。'); // 步骤3：非数字验证

// 步骤4：负数验证
$data4 = new stdClass();
$data4->holdDays = -5;
r($backupTest->setHoldDaysTest($data4)) && p('holdDays') && e('『保留天数』应当是正整数。'); // 步骤4：负数验证

// 步骤5：零值验证
$data5 = new stdClass();
$data5->holdDays = 0;
r($backupTest->setHoldDaysTest($data5)) && p('holdDays') && e('『保留天数』应当是正整数。'); // 步骤5：零值验证