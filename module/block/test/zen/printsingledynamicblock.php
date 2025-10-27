#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleDynamicBlock();
timeout=0
cid=0

- 步骤1：验证productID视图变量被设置属性hasProductID @1
- 步骤2：验证productID值正确属性productID @1
- 步骤3：验证actions视图变量被设置属性hasActions @1
- 步骤4：验证users视图变量被设置属性hasUsers @1
- 步骤5：验证所有关键视图变量都被设置
 - 属性hasProductID @1
 - 属性hasActions @1
 - 属性hasUsers @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
zendata('action')->loadYaml('action_printsingledynamicblock', false, 2)->gen(30);
zendata('user')->loadYaml('user_printsingledynamicblock', false, 2)->gen(10);
zendata('product')->loadYaml('product_printsingledynamicblock', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result = $blockTest->printSingleDynamicBlockTest();
r($result) && p('hasProductID') && e('1'); // 步骤1：验证productID视图变量被设置
r($result) && p('productID') && e('1'); // 步骤2：验证productID值正确
r($result) && p('hasActions') && e('1'); // 步骤3：验证actions视图变量被设置
r($result) && p('hasUsers') && e('1'); // 步骤4：验证users视图变量被设置
r($result) && p('hasProductID,hasActions,hasUsers') && e('1,1,1'); // 步骤5：验证所有关键视图变量都被设置