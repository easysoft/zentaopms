#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getMarket();
timeout=0
cid=19444

- 步骤1：验证ID属性id @1
- 步骤2：验证名称属性name @Test Market
- 步骤3：验证规模默认值属性scale @0
- 步骤4：验证创建者属性openedBy @admin
- 步骤5：验证删除状态属性deleted @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getMarketTest()) && p('id') && e('1'); // 步骤1：验证ID
r($tutorialTest->getMarketTest()) && p('name') && e('Test Market'); // 步骤2：验证名称
r($tutorialTest->getMarketTest()) && p('scale') && e('0'); // 步骤3：验证规模默认值
r($tutorialTest->getMarketTest()) && p('openedBy') && e('admin'); // 步骤4：验证创建者
r($tutorialTest->getMarketTest()) && p('deleted') && e('0'); // 步骤5：验证删除状态