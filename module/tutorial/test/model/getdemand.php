#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDemand();
timeout=0
cid=19418

- 步骤1：正常获取需求，验证ID属性id @1
- 步骤2：验证标题属性属性title @Test Demand
- 步骤3：验证状态属性属性status @active
- 步骤4：验证优先级属性属性pri @3
- 步骤5：验证池ID和分类属性
 - 属性pool @1
 - 属性category @feature

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDemandTest()) && p('id') && e('1');                              // 步骤1：正常获取需求，验证ID
r($tutorialTest->getDemandTest()) && p('title') && e('Test Demand');                 // 步骤2：验证标题属性
r($tutorialTest->getDemandTest()) && p('status') && e('active');                     // 步骤3：验证状态属性
r($tutorialTest->getDemandTest()) && p('pri') && e('3');                             // 步骤4：验证优先级属性
r($tutorialTest->getDemandTest()) && p('pool,category') && e('1,feature');           // 步骤5：验证池ID和分类属性