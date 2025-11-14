#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::getObjectFields();
timeout=0
cid=15955

- 步骤1：验证返回结果非空 @1
- 步骤2：验证包含product对象 @1
- 步骤3：验证包含user对象 @1
- 步骤4：验证包含story对象 @1
- 步骤5：验证包含task对象 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$dataviewTest = new dataviewTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$result = $dataviewTest->getObjectFieldsTest();

r(count($result) > 0) && p() && e('1'); // 步骤1：验证返回结果非空
r(isset($result['product'])) && p() && e('1'); // 步骤2：验证包含product对象
r(isset($result['user'])) && p() && e('1'); // 步骤3：验证包含user对象  
r(isset($result['story'])) && p() && e('1'); // 步骤4：验证包含story对象
r(isset($result['task'])) && p() && e('1'); // 步骤5：验证包含task对象