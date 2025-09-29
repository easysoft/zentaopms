#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinChartSQL();
timeout=0
cid=0

- 步骤1：测试插入操作生成的SQL数量 @182
- 步骤2：测试第一条SQL包含年度总结图表 @212
- 步骤3：测试插入SQL包含INSERT语句 @0
- 步骤4：测试更新操作SQL数量 @182
- 步骤5：测试更新SQL包含UPDATE语句 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($biTest->prepareBuiltinChartSQLTest('insert'))) && p('') && e('182'); // 步骤1：测试插入操作生成的SQL数量
r(strpos($biTest->prepareBuiltinChartSQLTest('insert')[0], 'annualSummary_countLogin')) && p('') && e('212'); // 步骤2：测试第一条SQL包含年度总结图表
r(strpos($biTest->prepareBuiltinChartSQLTest('insert')[0], 'INSERT INTO')) && p('') && e('0'); // 步骤3：测试插入SQL包含INSERT语句
r(count($biTest->prepareBuiltinChartSQLTest('update'))) && p('') && e('182'); // 步骤4：测试更新操作SQL数量
r(strpos($biTest->prepareBuiltinChartSQLTest('update')[0], 'UPDATE')) && p('') && e('0'); // 步骤5：测试更新SQL包含UPDATE语句