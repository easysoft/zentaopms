#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::parseToStatement();
timeout=0
cid=0

- 测试简单SELECT语句 @object
- 测试带WHERE条件的SELECT语句 @object
- 测试带JOIN的复杂SQL语句 @object
- 测试空字符串 @0
- 测试无效SQL语句 @0

*/

$bi = new biTest();

r($bi->parseToStatementTest('SELECT id, name FROM zt_user')) && p() && e('object');                                          // 测试简单SELECT语句
r($bi->parseToStatementTest('SELECT id, name FROM zt_user WHERE deleted = "0"')) && p() && e('object');                    // 测试带WHERE条件的SELECT语句
r($bi->parseToStatementTest('SELECT u.id, u.name FROM zt_user u LEFT JOIN zt_group g ON u.id = g.account')) && p() && e('object'); // 测试带JOIN的复杂SQL语句
r($bi->parseToStatementTest('')) && p() && e('0');                                                                           // 测试空字符串
r($bi->parseToStatementTest('INVALID SQL QUERY')) && p() && e('0');                                                         // 测试无效SQL语句