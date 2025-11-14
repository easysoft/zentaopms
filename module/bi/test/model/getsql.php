#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getSQL();
timeout=0
cid=15180

- 测试mysql驱动，简单SELECT语句，默认分页 @2
- 测试mysql驱动，带WHERE条件，自定义分页 @2
- 测试duckdb驱动，返回数组长度 @2
- 测试dm驱动，返回数组长度 @2
- 测试复杂JOIN查询，mysql驱动 @2

*/

$bi = new biTest();

r($bi->getSQLTest('SELECT id FROM zt_user', 'mysql')) && p() && e('2');                                      // 测试mysql驱动，简单SELECT语句，默认分页
r($bi->getSQLTest('SELECT id FROM zt_user WHERE id > 0', 'mysql', 5, 2)) && p() && e('2');                 // 测试mysql驱动，带WHERE条件，自定义分页
r($bi->getSQLTest('SELECT id FROM zt_user', 'duckdb')) && p() && e('2');                                    // 测试duckdb驱动，返回数组长度
r($bi->getSQLTest('SELECT id FROM zt_user', 'dm')) && p() && e('2');                                        // 测试dm驱动，返回数组长度
r($bi->getSQLTest('SELECT u.id FROM zt_user u JOIN zt_dept d ON u.dept = d.id', 'mysql')) && p() && e('2'); // 测试复杂JOIN查询，mysql驱动