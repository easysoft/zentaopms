#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinScreenSQL();
timeout=0
cid=0

- 步骤1：测试insert操作 @notempty
- 步骤2：测试update操作 @notempty
- 步骤3：验证生成INSERT语句 @*INSERT INTO*
- 步骤4：验证生成UPDATE语句 @*UPDATE*
- 步骤5：无效参数处理 @notempty

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

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
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->prepareBuiltinScreenSQLTest('insert')) && p() && e('notempty'); // 步骤1：测试insert操作
r($biTest->prepareBuiltinScreenSQLTest('update')) && p() && e('notempty'); // 步骤2：测试update操作
r($biTest->prepareBuiltinScreenSQLTest('insert')) && p('0') && e('*INSERT INTO*'); // 步骤3：验证生成INSERT语句
r($biTest->prepareBuiltinScreenSQLTest('update')) && p('0') && e('*UPDATE*'); // 步骤4：验证生成UPDATE语句
r($biTest->prepareBuiltinScreenSQLTest('invalid')) && p() && e('notempty'); // 步骤5：无效参数处理