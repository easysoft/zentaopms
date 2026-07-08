#!/usr/bin/env php
<?php

/**

title=测试 biModel::validateSql();
timeout=0
cid=15219

- 正常简单SELECT语句 @1
- 空SQL语句 @请输入一条正确的SQL语句
- 语法错误的SQL语句 @1
- 包含重复字段的SQL语句 @1
- 非SELECT语句 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$biTest = new biModelTest();

r($biTest->validateSqlTest('SELECT id, account FROM zt_user LIMIT 1')) && p() && e('1'); // 正常简单SELECT语句
r($biTest->validateSqlTest('')) && p() && e('请输入一条正确的SQL语句'); // 空SQL语句
r(strpos($biTest->validateSqlTest('SELECT * FROM zt_nonexistent_table'), 'zt_nonexistent_table') !== false) && p() && e('1'); // 语法错误的SQL语句
r($biTest->validateSqlTest('SELECT id as name, account as name FROM zt_user')) && p() && e('1'); // 包含重复字段的SQL语句
r(strpos($biTest->validateSqlTest('INSERT INTO zt_user VALUES(1)'), "Column count doesn't match value count") !== false) && p() && e('1'); // 非SELECT语句
