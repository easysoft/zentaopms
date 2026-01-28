#!/usr/bin/env php
<?php

/**

title=测试 biModel::validateSql();
timeout=0
cid=15219

- 正常简单SELECT语句 @1
- 空SQL语句 @请输入一条正确的SQL语句
- 语法错误的SQL语句 @Table 'zttest.zt_nonexistent_table' doesn't exist
- 包含重复字段的SQL语句 @存在重复的字段名： name。建议您：（1）修改 * 查询为具体的字段。（2）使用 as 为字段设置别名。
- 非SELECT语句 @You have an error in your SQL syntax

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建测试实例
$biTest = new biModelTest();

r($biTest->validateSqlTest('SELECT id, account FROM zt_user LIMIT 1')) && p() && e('1'); // 正常简单SELECT语句
r($biTest->validateSqlTest('')) && p() && e('请输入一条正确的SQL语句'); // 空SQL语句
r($biTest->validateSqlTest('SELECT * FROM zt_nonexistent_table')) && p() && e("Table 'zttest.zt_nonexistent_table' doesn't exist"); // 语法错误的SQL语句
r($biTest->validateSqlTest('SELECT id as name, account as name FROM zt_user')) && p() && e('存在重复的字段名： name。建议您：（1）修改 * 查询为具体的字段。（2）使用 as 为字段设置别名。'); // 包含重复字段的SQL语句
r($biTest->validateSqlTest('INSERT INTO zt_user VALUES(1)')) && p() && e('You have an error in your SQL syntax'); // 非SELECT语句