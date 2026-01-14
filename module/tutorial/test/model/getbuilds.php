#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBuilds();
timeout=0
cid=19409

- 步骤1：正常调用，验证返回数组长度为1 @1
- 步骤2：验证返回数组包含版本ID为1的对象第1条的id属性 @1
- 步骤3：验证版本名称为"Test build"第1条的name属性 @Test build
- 步骤4：验证版本所属产品ID为1第1条的product属性 @1
- 步骤5：验证执行名称第1条的executionName属性 @Test execution

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$builds = $tutorialTest->getBuildsTest();
r(count($builds)) && p() && e(1); // 步骤1：正常调用，验证返回数组长度为1
r($builds) && p('1:id') && e('1'); // 步骤2：验证返回数组包含版本ID为1的对象
r($builds) && p('1:name') && e('Test build'); // 步骤3：验证版本名称为"Test build"
r($builds) && p('1:product') && e('1'); // 步骤4：验证版本所属产品ID为1
r($builds) && p('1:executionName') && e('Test execution'); // 步骤5：验证执行名称