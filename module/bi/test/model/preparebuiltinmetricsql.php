#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinMetricSQL();
timeout=0
cid=0

- 步骤1：insert操作返回非空数组 @1
- 步骤2：update操作返回非空数组 @1
- 步骤3：验证生成INSERT语句 @1
- 步骤4：update返回数组类型 @1
- 步骤5：无效参数返回数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 为了测试稳定性，我们不依赖现有数据，直接测试方法功能

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($biTest->prepareBuiltinMetricSQLTest('insert')) > 0) && p() && e('1'); // 步骤1：insert操作返回非空数组
r(count($biTest->prepareBuiltinMetricSQLTest('update')) > 0) && p() && e('1'); // 步骤2：update操作返回非空数组
r(strpos($biTest->prepareBuiltinMetricSQLTest('insert')[0], 'INSERT INTO `zt_metric`') !== false) && p() && e('1'); // 步骤3：验证生成INSERT语句
r(is_array($biTest->prepareBuiltinMetricSQLTest('update'))) && p() && e('1'); // 步骤4：update返回数组类型
r(is_array($biTest->prepareBuiltinMetricSQLTest('invalid'))) && p() && e('1'); // 步骤5：无效参数返回数组