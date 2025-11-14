#!/usr/bin/env php
<?php

/**

title=测试 metricModel::parseSqlFunction();
timeout=0
cid=17146

- 步骤1：标准CREATE FUNCTION语句 @calculate_score
- 步骤2：小写CREATE FUNCTION语句 @user_count
- 步骤3：复杂函数名测试 @complex_func_123
- 步骤4：不完整语句测试 @0
- 步骤5：空字符串测试 @0
- 步骤6：无效SQL语句测试 @0
- 步骤7：多行语句测试 @multiline_func

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metricTest = new metricTest();

// 测试数据准备
$standardFunc     = 'CREATE FUNCTION `calculate_score`(';
$lowercaseFunc    = 'create function `user_count`(';
$complexFunc      = 'CREATE FUNCTION `complex_func_123`(';
$incompleteFunc   = 'create function `incomplete_func`';
$emptyString      = '';
$invalidSql       = 'SELECT * FROM users';
$multilineFunc    = "CREATE FUNCTION `multiline_func`(\n    param1 INT,\n    param2 VARCHAR(50)\n)(";

r($metricTest->parseSqlFunction($standardFunc)) && p() && e('calculate_score');      // 步骤1：标准CREATE FUNCTION语句
r($metricTest->parseSqlFunction($lowercaseFunc)) && p() && e('user_count');         // 步骤2：小写CREATE FUNCTION语句
r($metricTest->parseSqlFunction($complexFunc)) && p() && e('complex_func_123');     // 步骤3：复杂函数名测试
r($metricTest->parseSqlFunction($incompleteFunc)) && p() && e('0');                // 步骤4：不完整语句测试
r($metricTest->parseSqlFunction($emptyString)) && p() && e('0');                   // 步骤5：空字符串测试
r($metricTest->parseSqlFunction($invalidSql)) && p() && e('0');                    // 步骤6：无效SQL语句测试
r($metricTest->parseSqlFunction($multilineFunc)) && p() && e('multiline_func');    // 步骤7：多行语句测试