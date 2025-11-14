#!/usr/bin/env php
<?php

/**

title=测试 commonModel::isOpenMethod();
timeout=0
cid=15683

- 步骤1：公开方法权限验证 @1
- 步骤2：登录后方法权限验证 @1
- 步骤3：非公开方法权限验证 @0
- 步骤4：特殊模块方法权限验证 @1
- 步骤5：Ajax方法权限验证 @1
- 步骤6：block模块特殊方法权限验证 @1
- 步骤7：不存在方法权限验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（已登录状态进行测试）
su('admin');

// 3. 创建测试实例
$commonTest = new commonTest();

// 4. 测试步骤：必须包含至少5个测试步骤
r($commonTest->isOpenMethodTest('misc', 'changelog')) && p() && e('1');        // 步骤1：公开方法权限验证
r($commonTest->isOpenMethodTest('tutorial', 'quit')) && p() && e('1');         // 步骤2：登录后方法权限验证
r($commonTest->isOpenMethodTest('my', 'task')) && p() && e('0');               // 步骤3：非公开方法权限验证
r($commonTest->isOpenMethodTest('product', 'showerrornone')) && p() && e('1'); // 步骤4：特殊模块方法权限验证
r($commonTest->isOpenMethodTest('user', 'ajaxGetList')) && p() && e('1');      // 步骤5：Ajax方法权限验证
r($commonTest->isOpenMethodTest('block', 'dashboard')) && p() && e('1');       // 步骤6：block模块特殊方法权限验证
r($commonTest->isOpenMethodTest('unknown', 'invalidmethod')) && p() && e('0'); // 步骤7：不存在方法权限验证