#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getSubSpaces();
timeout=0
cid=19480

- 步骤1：测试'custom'参数属性1 @Test Team Space
- 步骤2：测试'mine'参数属性1 @Test My Space
- 步骤3：测试空字符串参数 @0
- 步骤4：测试无效参数 @0
- 步骤5：测试默认参数属性1 @Test Team Space

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getSubSpacesTest('custom')) && p('1') && e('Test Team Space'); // 步骤1：测试'custom'参数
r($tutorialTest->getSubSpacesTest('mine')) && p('1') && e('Test My Space'); // 步骤2：测试'mine'参数
r($tutorialTest->getSubSpacesTest('')) && p() && e('0'); // 步骤3：测试空字符串参数
r($tutorialTest->getSubSpacesTest('invalid')) && p() && e('0'); // 步骤4：测试无效参数
r($tutorialTest->getSubSpacesTest()) && p('1') && e('Test Team Space'); // 步骤5：测试默认参数