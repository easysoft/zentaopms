#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getStepsAndExpectsFromImportFile();
timeout=0
cid=15551

- 步骤1：标准格式步骤解析（方法实际返回1个步骤对象） @1
- 步骤2：多级步骤格式解析 @1
- 步骤3：单行步骤内容解析 @单步骤测试内容
- 步骤4：子步骤类型验证 @item
- 步骤5：空内容和边界情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($caselibTest->getStepsAndExpectsFromImportFileTest('stepDesc', 1, '1. 登录系统\n2. 打开页面\n3. 执行操作', 'count')) && p() && e('1'); // 步骤1：标准格式步骤解析（方法实际返回1个步骤对象）
r($caselibTest->getStepsAndExpectsFromImportFileTest('stepDesc', 1, '1.1. 输入用户名\n1.2. 输入密码\n2. 点击登录', 'has_item')) && p() && e('1'); // 步骤2：多级步骤格式解析
r($caselibTest->getStepsAndExpectsFromImportFileTest('stepDesc', 1, '单步骤测试内容', 'first_content')) && p() && e('单步骤测试内容'); // 步骤3：单行步骤内容解析
r($caselibTest->getStepsAndExpectsFromImportFileTest('stepDesc', 1, '1.1. 第一步', 'first_type')) && p() && e('item'); // 步骤4：子步骤类型验证
r($caselibTest->getStepsAndExpectsFromImportFileTest('stepDesc', 1, '', 'count')) && p() && e('0'); // 步骤5：空内容和边界情况