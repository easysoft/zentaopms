#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::parseTableList();
timeout=0
cid=0

- 测试解析简单SELECT语句，包含单个表且无别名 属性zt_user @zt_user
- 测试解析带表别名的SELECT语句 属性u @zt_user
- 测试解析包含JOIN的复杂SQL语句，验证多表处理 
 - 属性u @zt_user
 - 属性p @zt_product
- 测试解析包含子查询别名的SELECT语句 属性user_data @SELECT * FROM zt_user
- 测试解析无效SQL语句，验证错误处理 @0

*/

su('admin');

$bi = new biTest();

r($bi->parseTableListTest('SELECT * FROM zt_user')) && p('zt_user') && e('zt_user'); // 测试解析简单SELECT语句，包含单个表且无别名
r($bi->parseTableListTest('SELECT u.account FROM zt_user u')) && p('u') && e('zt_user'); // 测试解析带表别名的SELECT语句
r($bi->parseTableListTest('SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.createdBy')) && p('u,p') && e('zt_user,zt_product'); // 测试解析包含JOIN的复杂SQL语句，验证多表处理
r($bi->parseTableListTest('SELECT * FROM (SELECT * FROM zt_user) AS user_data')) && p('user_data') && e('SELECT * FROM zt_user'); // 测试解析包含子查询别名的SELECT语句
r($bi->parseTableListTest('INVALID SQL')) && p() && e('0'); // 测试解析无效SQL语句，验证错误处理