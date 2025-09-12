#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectOverviewBlock();
timeout=0
cid=0

- 步骤1：正常情况-验证返回2个组 @2
- 步骤2：空数据-验证返回2个组 @2
- 步骤3：部分数据-验证返回2个组 @2
- 步骤4：当年数据-验证返回2个组 @2
- 步骤5：最大值计算-验证返回2个组 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（不使用数据库数据，完全模拟）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printProjectOverviewBlockTest()) && p() && e('2'); // 步骤1：正常情况-验证返回2个组
r($blockTest->printProjectOverviewBlockTest('empty')) && p() && e('2'); // 步骤2：空数据-验证返回2个组
r($blockTest->printProjectOverviewBlockTest('partial')) && p() && e('2'); // 步骤3：部分数据-验证返回2个组
r($blockTest->printProjectOverviewBlockTest('current')) && p() && e('2'); // 步骤4：当年数据-验证返回2个组
r($blockTest->printProjectOverviewBlockTest('maxvalue')) && p() && e('2'); // 步骤5：最大值计算-验证返回2个组