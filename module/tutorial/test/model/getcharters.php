#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCharters();
timeout=0
cid=19414

- 步骤1：正常调用返回数组长度为1 @1
- 步骤2：验证charter对象ID为1第1条的id属性 @1
- 步骤3：验证charter对象名称第1条的name属性 @Test charter
- 步骤4：验证charter对象状态第1条的status属性 @wait
- 步骤5：验证charter对象级别第1条的level属性 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getChartersTest())) && p() && e('1'); // 步骤1：正常调用返回数组长度为1
r($tutorialTest->getChartersTest()) && p('1:id') && e('1'); // 步骤2：验证charter对象ID为1
r($tutorialTest->getChartersTest()) && p('1:name') && e('Test charter'); // 步骤3：验证charter对象名称
r($tutorialTest->getChartersTest()) && p('1:status') && e('wait'); // 步骤4：验证charter对象状态
r($tutorialTest->getChartersTest()) && p('1:level') && e('3'); // 步骤5：验证charter对象级别