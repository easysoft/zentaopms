#!/usr/bin/env php
<?php

/**

title=测试 reportModel::__construct();
timeout=0
cid=18158

- 步骤1：验证对象类型属性isReportModel @1
- 步骤2：验证dao对象存在属性hasDao @1
- 步骤3：验证config对象存在属性hasConfig @1
- 步骤4：验证lang对象存在属性hasLang @1
- 步骤5：验证所有属性均正确初始化
 - 属性isReportModel @1
 - 属性hasDao @1
 - 属性hasConfig @1
 - 属性hasLang @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$reportTest = new reportModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->__constructTest()) && p('isReportModel') && e('1');     // 步骤1：验证对象类型
r($reportTest->__constructTest()) && p('hasDao') && e('1');           // 步骤2：验证dao对象存在
r($reportTest->__constructTest()) && p('hasConfig') && e('1');        // 步骤3：验证config对象存在
r($reportTest->__constructTest()) && p('hasLang') && e('1');          // 步骤4：验证lang对象存在
r($reportTest->__constructTest()) && p('isReportModel,hasDao,hasConfig,hasLang') && e('1,1,1,1'); // 步骤5：验证所有属性均正确初始化