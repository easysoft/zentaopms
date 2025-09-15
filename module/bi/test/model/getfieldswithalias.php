#!/usr/bin/env php
<?php

/**

title=测试 biModel::getFieldsWithAlias();
timeout=0
cid=0

- 步骤1：正常字段解析
 - 属性id @id
 - 属性account @account
 - 属性realname @realname
- 步骤2：带别名字段解析
 - 属性user_id @id
 - 属性username @account
- 步骤3：表别名字段解析
 - 属性id @id
 - 属性account @account
 - 属性realname @realname
- 步骤4：多表连接解析
 - 属性account @account
 - 属性name @name
- 步骤5：无效SQL处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getFieldsWithAliasTest('SELECT id, account, realname FROM zt_user')) && p('id,account,realname') && e('id,account,realname'); // 步骤1：正常字段解析
r($biTest->getFieldsWithAliasTest('SELECT id AS user_id, account AS username FROM zt_user')) && p('user_id,username') && e('id,account'); // 步骤2：带别名字段解析
r($biTest->getFieldsWithAliasTest('SELECT u.id, u.account, u.realname FROM zt_user u')) && p('id,account,realname') && e('id,account,realname'); // 步骤3：表别名字段解析
r($biTest->getFieldsWithAliasTest('SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id')) && p('account,name') && e('account,name'); // 步骤4：多表连接解析
r($biTest->getFieldsWithAliasTest('INVALID SQL STATEMENT')) && p() && e('0'); // 步骤5：无效SQL处理