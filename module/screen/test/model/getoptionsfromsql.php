#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getOptionsFromSql();
timeout=0
cid=18250

- 执行screenTest模块的getOptionsFromSqlTest方法，参数是"SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 3", 'id', 'account' 
 - 属性1 @admin
 - 属性2 @test1
 - 属性3 @test2
- 执行screenTest模块的getOptionsFromSqlTest方法，参数是"SELECT id, account FROM " . TABLE_USER . " WHERE id > 999", 'id', 'account'  @0
- 执行screenTest模块的getOptionsFromSqlTest方法，参数是"SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 2", 'nonexistent', 'account'  @0
- 执行screenTest模块的getOptionsFromSqlTest方法，参数是"SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 2", 'id', 'nonexistent'  @0
- 执行screenTest模块的getOptionsFromSqlTest方法，参数是"SELECT id, realname FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id", 'id', 'realname' 
 - 属性1 @管理员
 - 属性2 @测试用户1
 - 属性3 @测试用户2
 - 属性4 @用户1
 - 属性5 @用户2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,test1,test2,user1,user2');
$userTable->realname->range('管理员,测试用户1,测试用户2,用户1,用户2');
$userTable->role->range('admin,dev,qa,user,guest');
$userTable->deleted->range('0');
$userTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$screenTest = new screenTest();

// 5. 执行至少5个测试步骤
r($screenTest->getOptionsFromSqlTest("SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 3", 'id', 'account')) && p('1,2,3') && e('admin,test1,test2');
r($screenTest->getOptionsFromSqlTest("SELECT id, account FROM " . TABLE_USER . " WHERE id > 999", 'id', 'account')) && p() && e('0');
r($screenTest->getOptionsFromSqlTest("SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 2", 'nonexistent', 'account')) && p() && e('0');
r($screenTest->getOptionsFromSqlTest("SELECT id, account FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id LIMIT 2", 'id', 'nonexistent')) && p() && e('0');
r($screenTest->getOptionsFromSqlTest("SELECT id, realname FROM " . TABLE_USER . " WHERE deleted='0' ORDER BY id", 'id', 'realname')) && p('1,2,3,4,5') && e('管理员,测试用户1,测试用户2,用户1,用户2');