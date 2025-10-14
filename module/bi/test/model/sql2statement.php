#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::sql2Statement();
timeout=0
cid=0

- 步骤1：正常SELECT语句 @object
- 步骤2：空SQL语句 @请输入一条正确的SQL语句
- 步骤3：非SELECT语句 @只允许SELECT查询
- 步骤4：多个语句 @只能输入一条SQL语句
- 步骤5：builder模式空语句 @请正确配置构建器

*/

su('admin');

$bi = new biTest();

r($bi->sql2StatementTest('SELECT id, name FROM zt_user WHERE id = 1')) && p() && e('object'); // 步骤1：正常SELECT语句
r($bi->sql2StatementTest('')) && p() && e('请输入一条正确的SQL语句'); // 步骤2：空SQL语句
r($bi->sql2StatementTest('INSERT INTO zt_user (name) VALUES ("test")')) && p() && e('只允许SELECT查询'); // 步骤3：非SELECT语句
r($bi->sql2StatementTest('SELECT id FROM zt_user; SELECT name FROM zt_task;')) && p() && e('只能输入一条SQL语句'); // 步骤4：多个语句
r($bi->sql2StatementTest('', 'builder')) && p() && e('请正确配置构建器'); // 步骤5：builder模式空语句