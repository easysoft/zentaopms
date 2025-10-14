#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printMonthlyProgressBlock();
timeout=0
cid=0

- 执行$result1->type == 'success @1
- 执行$result2->dataCount == 6 @1
- 执行$result3->hasValidDateKeys @1
- 执行$result4->hasViewData @1
- 执行$result5->expectedDataCount == 6 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：验证方法执行成功
$result1 = $blockTest->printMonthlyProgressBlockTest();
r($result1->type == 'success') && p() && e('1');

// 步骤2：验证数据数量正确
$result2 = $blockTest->printMonthlyProgressBlockTest();
r($result2->dataCount == 6) && p() && e('1');

// 步骤3：验证日期格式正确
$result3 = $blockTest->printMonthlyProgressBlockTest();
r($result3->hasValidDateKeys) && p() && e('1');

// 步骤4：验证view数据完整性
$result4 = $blockTest->printMonthlyProgressBlockTest();
r($result4->hasViewData) && p() && e('1');

// 步骤5：验证期望数据数量
$result5 = $blockTest->printMonthlyProgressBlockTest();
r($result5->expectedDataCount == 6) && p() && e('1');