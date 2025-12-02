#!/usr/bin/env php
<?php

/**

title=测试 biModel::getFieldsWithAlias();
timeout=0
cid=15172

- 执行biTest模块的getFieldsWithAliasTest方法，参数是'SELECT id, account, realname FROM zt_user'
 - 属性id @id
 - 属性account @account
 - 属性realname @realname
- 执行biTest模块的getFieldsWithAliasTest方法，参数是'SELECT id AS user_id, account AS username FROM zt_user'
 - 属性user_id @id
 - 属性username @account
- 执行biTest模块的getFieldsWithAliasTest方法，参数是'SELECT u.id, u.account, u.realname FROM zt_user u'
 - 属性id @id
 - 属性account @account
 - 属性realname @realname
- 执行biTest模块的getFieldsWithAliasTest方法，参数是'SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id'
 - 属性account @account
 - 属性name @name
- 执行biTest模块的getFieldsWithAliasTest方法，参数是'INVALID SQL STATEMENT'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

$biTest = new biTest();

// 测试1: 正常字段解析
r($biTest->getFieldsWithAliasTest('SELECT id, account, realname FROM zt_user')) && p('id,account,realname') && e('id,account,realname');

// 测试2: 带别名字段解析
r($biTest->getFieldsWithAliasTest('SELECT id AS user_id, account AS username FROM zt_user')) && p('user_id,username') && e('id,account');

// 测试3: 表别名字段解析
r($biTest->getFieldsWithAliasTest('SELECT u.id, u.account, u.realname FROM zt_user u')) && p('id,account,realname') && e('id,account,realname');

// 测试4: 多表连接解析
r($biTest->getFieldsWithAliasTest('SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id')) && p('account,name') && e('account,name');

// 测试5: 无效SQL处理
r($biTest->getFieldsWithAliasTest('INVALID SQL STATEMENT')) && p() && e('0');