#!/usr/bin/env php
<?php

/**

title=测试 commonModel::setMenuVars();
timeout=0
cid=15712

- 步骤1：正常情况 @browse-123
- 步骤2：带html后缀链接 @view-456.html
- 步骤3：webMenu模式 @browse-789
- 步骤4：空菜单项跳过 @browse-999
- 步骤5：homeMenu删除 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($commonTest->setMenuVarsTest(1)) && p() && e('browse-123'); // 步骤1：正常情况
r($commonTest->setMenuVarsTest(2)) && p() && e('view-456.html'); // 步骤2：带html后缀链接
r($commonTest->setMenuVarsTest(3)) && p() && e('browse-789'); // 步骤3：webMenu模式
r($commonTest->setMenuVarsTest(4)) && p() && e('browse-999'); // 步骤4：空菜单项跳过
r($commonTest->setMenuVarsTest(5)) && p() && e('1'); // 步骤5：homeMenu删除