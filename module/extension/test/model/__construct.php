#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::__construct();
timeout=0
cid=0

- 步骤1：正常实例化验证类名 @extensionModel
- 步骤2：验证apiRoot属性 @https://api.zentao.net/extension-
- 步骤3：验证classFile类 @zfile
- 步骤4：验证pkgRoot包含pkg @1
- 步骤5：验证继承基类 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(get_class($extensionTest->__constructTest())) && p() && e('extensionModel'); // 步骤1：正常实例化验证类名
r($extensionTest->__constructTest()->apiRoot) && p() && e('https://api.zentao.net/extension-'); // 步骤2：验证apiRoot属性
r(get_class($extensionTest->__constructTest()->classFile)) && p() && e('zfile'); // 步骤3：验证classFile类
r(strpos($extensionTest->__constructTest()->pkgRoot, 'pkg') !== false) && p() && e('1'); // 步骤4：验证pkgRoot包含pkg
r($extensionTest->__constructTest() instanceof model) && p() && e('1'); // 步骤5：验证继承基类