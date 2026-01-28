#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinScreenSQL();
timeout=0
cid=15200

- 步骤1：测试insert操作返回数组 @array
- 步骤2：测试update操作返回数组 @array
- 步骤3：验证insert生成INSERT语句内容 @0
- 步骤4：验证update生成INSERT语句内容 @0
- 步骤5：测试无效操作参数处理 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('screen');
$table->id->range('1-10');
$table->name->range('test screen{1-10}');
$table->status->range('published');
$table->builtin->range('1');
$table->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->prepareBuiltinScreenSQLTest('insert')) && p() && e('array'); // 步骤1：测试insert操作返回数组
r($biTest->prepareBuiltinScreenSQLTest('update')) && p() && e('array'); // 步骤2：测试update操作返回数组
r(strpos($biTest->prepareBuiltinScreenSQLContentTest('insert')[0], 'INSERT INTO')) && p('') && e('0'); // 步骤3：验证insert生成INSERT语句内容
r(strpos($biTest->prepareBuiltinScreenSQLContentTest('update')[0], 'INSERT INTO')) && p('') && e('0'); // 步骤4：验证update生成INSERT语句内容
r($biTest->prepareBuiltinScreenSQLTest('invalid')) && p() && e('array'); // 步骤5：测试无效操作参数处理