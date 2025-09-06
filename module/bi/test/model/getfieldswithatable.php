#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getFieldsWithTable();
timeout=0
cid=0

- 执行biTest模块的getFieldsWithTableTest方法，参数是'SELECT id, account, realname FROM zt_user' 
 - 属性account @zt_user
 - 属性id @zt_user
 - 属性realname @zt_user
- 执行biTest模块的getFieldsWithTableTest方法，参数是'SELECT u.id, u.account, u.realname FROM zt_user u' 
 - 属性account @zt_user
 - 属性id @zt_user
 - 属性realname @zt_user
- 执行biTest模块的getFieldsWithTableTest方法，参数是'SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id' 
 - 属性account @zt_user
 - 属性name @zt_product
- 执行biTest模块的getFieldsWithTableTest方法，参数是'SELECT u.account AS user_account, u.realname AS user_name FROM zt_user u' 
 - 属性user_account @zt_user
 - 属性user_name @zt_user
- 执行biTest模块的getFieldsWithTableTest方法，参数是'INVALID SQL STATEMENT'  @0

*/

su('admin');

$biTest = new biTest();

// 测试1：简单单表查询
r($biTest->getFieldsWithTableTest('SELECT id, account, realname FROM zt_user')) && p('account,id,realname') && e('zt_user,zt_user,zt_user');

// 测试2：带表别名查询
r($biTest->getFieldsWithTableTest('SELECT u.id, u.account, u.realname FROM zt_user u')) && p('account,id,realname') && e('zt_user,zt_user,zt_user');

// 测试3：多表连接查询
r($biTest->getFieldsWithTableTest('SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id')) && p('account,name') && e('zt_user,zt_product');

// 测试4：带列别名查询
r($biTest->getFieldsWithTableTest('SELECT u.account AS user_account, u.realname AS user_name FROM zt_user u')) && p('user_account,user_name') && e('zt_user,zt_user');

// 测试5：无效SQL处理
r($biTest->getFieldsWithTableTest('INVALID SQL STATEMENT')) && p() && e('0');