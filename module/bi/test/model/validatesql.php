#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::validateSql();
timeout=0
cid=0

- 正常简单SELECT语句 @1
- 空SQL语句 @查询为空。
- 语法错误的SQL语句 @~Table.*doesn.*t exist~
- 包含重复字段的SQL语句 @~字段名重复~
- 非SELECT语句 @~You have an error in your SQL syntax~

*/

$biTest = new biTest();

r($biTest->validateSqlTest('SELECT id, account FROM zt_user LIMIT 1')) && p() && e('1'); // 正常简单SELECT语句
r($biTest->validateSqlTest('')) && p() && e('查询为空。'); // 空SQL语句
r($biTest->validateSqlTest('SELECT * FROM nonexistent_table')) && p() && e('~Table.*doesn.*t exist~'); // 语法错误的SQL语句
r($biTest->validateSqlTest('SELECT id as duplicate, account as duplicate FROM zt_user')) && p() && e('~字段名重复~'); // 包含重复字段的SQL语句
r($biTest->validateSqlTest('INSERT INTO zt_user (account) VALUES ("test")')) && p() && e('~You have an error in your SQL syntax~'); // 非SELECT语句