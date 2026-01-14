#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::execDrillSQL();
timeout=0
cid=17362

- 步骤1：正常情况属性status @success
- 步骤2：限制数量属性status @success
- 步骤3：空SQL属性status @fail
- 步骤4：无效SQL属性status @fail
- 步骤5：边界值limit为0属性status @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 模拟最基本的数据，不使用zenData避免输出干扰
// 测试execDrillSQL方法不需要实际的数据库数据，它主要测试SQL执行逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->execDrillSQLTest('user', 'SELECT 1 as test', 10)) && p('status') && e('success'); // 步骤1：正常情况
r($pivotTest->execDrillSQLTest('user', 'SELECT * FROM zt_user WHERE id > 0 LIMIT 2', 5)) && p('status') && e('success'); // 步骤2：限制数量
r($pivotTest->execDrillSQLTest('user', '', 10)) && p('status') && e('fail'); // 步骤3：空SQL
r($pivotTest->execDrillSQLTest('user', 'INVALID SQL SYNTAX', 10)) && p('status') && e('fail'); // 步骤4：无效SQL
r($pivotTest->execDrillSQLTest('user', 'SELECT 2 as test', 0)) && p('status') && e('success'); // 步骤5：边界值limit为0