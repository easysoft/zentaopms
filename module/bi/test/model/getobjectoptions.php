#!/usr/bin/env php
<?php

/**

title=测试 biModel::getObjectOptions();
timeout=0
cid=0

- 步骤1：正常获取用户ID选项 @array
- 步骤2：获取产品名称选项 @array
- 步骤3：不存在的对象类型 @array
- 步骤4：不存在的字段 @array
- 步骤5：空参数测试 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getObjectOptionsTest('user', 'id')) && p() && e('array'); // 步骤1：正常获取用户ID选项
r($biTest->getObjectOptionsTest('product', 'name')) && p() && e('array'); // 步骤2：获取产品名称选项
r($biTest->getObjectOptionsTest('nonexistent', 'id')) && p() && e('array'); // 步骤3：不存在的对象类型
r($biTest->getObjectOptionsTest('user', 'nonexistent')) && p() && e('array'); // 步骤4：不存在的字段
r($biTest->getObjectOptionsTest('', '')) && p() && e('array'); // 步骤5：空参数测试