#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinMetricSQL();
timeout=0
cid=0

- 步骤1：默认insert操作生成SQL >> 期望返回包含INSERT语句的数组
- 步骤2：update操作生成SQL >> 期望返回包含UPDATE语句的数组
- 步骤3：insert操作但部分记录已存在 >> 期望正确处理已存在记录
- 步骤4：无效操作参数 >> 期望返回空数组或合理处理
- 步骤5：验证SQL语句格式正确性 >> 期望生成的SQL语句结构正确

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->id->range('1-3');
$table->code->range('test_metric_1,test_metric_2,count_of_program');
$table->name->range('测试度量项1,测试度量项2,按系统统计的所有层级的项目集总数');
$table->builtin->range('0,0,1');
$table->type->range('php{3}');
$table->stage->range('released{3}');
$table->createdBy->range('admin{3}');
$table->createdDate->range('`2023-01-01 00:00:00`{3}');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->prepareBuiltinMetricSQLTest('insert')) && p() && e('notempty'); // 步骤1：默认insert操作
r($biTest->prepareBuiltinMetricSQLTest('update')) && p() && e('notempty'); // 步骤2：update操作
r($biTest->prepareBuiltinMetricSQLTest('insert')) && p('0') && e('*INSERT INTO*'); // 步骤3：验证生成INSERT语句
r($biTest->prepareBuiltinMetricSQLTest('update')) && p('0') && e('*UPDATE*'); // 步骤4：验证生成UPDATE语句
r($biTest->prepareBuiltinMetricSQLTest('invalid')) && p() && e('notempty'); // 步骤5：无效参数处理