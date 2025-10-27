#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinChartSQL();
timeout=0
cid=0

- 步骤1：测试插入操作生成的SQL数量 @182
- 步骤2：测试第一条SQL包含年度总结图表代码 @1
- 步骤3：测试插入SQL包含INSERT语句 @1
- 步骤4：测试更新操作SQL数量 @182
- 步骤5：测试更新SQL包含UPDATE语句 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 3. 执行测试步骤
$insertResults = $biTest->prepareBuiltinChartSQLTest('insert');
$updateResults = $biTest->prepareBuiltinChartSQLTest('update');

r(count($insertResults)) && p() && e('182'); // 步骤1：测试插入操作生成的SQL数量
r((strpos($insertResults[0], 'annualSummary_countLogin') !== false ? 1 : 0)) && p() && e('1'); // 步骤2：测试第一条SQL包含年度总结图表代码
r((strpos($insertResults[0], 'INSERT INTO') !== false ? 1 : 0)) && p() && e('1'); // 步骤3：测试插入SQL包含INSERT语句
r(count($updateResults)) && p() && e('182'); // 步骤4：测试更新操作SQL数量
r((strpos($updateResults[0], 'UPDATE') !== false ? 1 : 0)) && p() && e('1'); // 步骤5：测试更新SQL包含UPDATE语句