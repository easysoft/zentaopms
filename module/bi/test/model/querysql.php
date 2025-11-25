#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::querySQL();
timeout=0
cid=15212

- 测试有效SQL查询，期望result为success @success
- 测试无效SQL语法，期望result为fail @fail
- 测试空SQL语句，期望result为fail @fail
- 测试不存在表的查询，期望result为fail @fail
- 测试count查询，期望result为success @success

*/

$bi = new biTest();

r($bi->querySQLTest('SELECT id FROM zt_user LIMIT 2', 'SELECT id FROM zt_user LIMIT 2', 'mysql')) && p('result') && e('success'); // 测试有效SQL查询
r($bi->querySQLTest('SELECT id FROM zt_user', 'SELECT * FORM invalid_syntax', 'mysql')) && p('result') && e('fail'); // 测试无效SQL语法
r($bi->querySQLTest('', '', 'mysql')) && p('result') && e('fail'); // 测试空SQL语句
r($bi->querySQLTest('SELECT * FROM non_existent_table', 'SELECT * FROM non_existent_table LIMIT 1', 'mysql')) && p('result') && e('fail'); // 测试不存在的表
r($bi->querySQLTest('SELECT count(*) as total FROM zt_user', 'SELECT count(*) as total FROM zt_user LIMIT 1', 'mysql')) && p('result') && e('success'); // 测试count查询