#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDynamicBlock();
timeout=0
cid=15260

- 步骤1：验证actions是数组 @1
- 步骤2：验证users是数组 @1
- 步骤3：验证actions数量（>=0） @1
- 步骤4：验证users数量（>0） @1
- 步骤5：验证两个属性都存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
zendata('action')->loadYaml('action_printdynamicblock', false, 2)->gen(30);
zendata('user')->loadYaml('user_printdynamicblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result = $blockTest->printDynamicBlockTest();
r(is_array($result->actions)) && p() && e('1'); // 步骤1：验证actions是数组
r(is_array($result->users)) && p() && e('1'); // 步骤2：验证users是数组
r(count($result->actions) >= 0) && p() && e('1'); // 步骤3：验证actions数量（>=0）
r(count($result->users) > 0) && p() && e('1'); // 步骤4：验证users数量（>0）
r(isset($result->actions) && isset($result->users)) && p() && e('1'); // 步骤5：验证两个属性都存在