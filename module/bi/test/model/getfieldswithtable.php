#!/usr/bin/env php
<?php

/**

title=测试 biModel::getFieldsWithTable();
timeout=0
cid=15173

- 步骤1：简单单表查询
 - 属性account @zt_user
 - 属性id @zt_user
 - 属性realname @zt_user
- 步骤2：带表别名查询
 - 属性account @zt_user
 - 属性id @zt_user
 - 属性realname @zt_user
- 步骤3：多表连接查询
 - 属性account @zt_user
 - 属性name @zt_product
- 步骤4：带列别名查询
 - 属性user_account @zt_user
 - 属性user_name @zt_user
- 步骤5：无效SQL处理 @0
- 步骤6：通配符查询
 - 属性account @zt_user
 - 属性avatar @zt_user
 - 属性birthday @zt_user
- 步骤7：复杂JOIN查询
 - 属性account @zt_user
 - 属性name @zt_product

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($biTest->getFieldsWithTableTest('SELECT id, account, realname FROM zt_user')) && p('account,id,realname') && e('zt_user,zt_user,zt_user'); // 步骤1：简单单表查询
r($biTest->getFieldsWithTableTest('SELECT u.id, u.account, u.realname FROM zt_user u')) && p('account,id,realname') && e('zt_user,zt_user,zt_user'); // 步骤2：带表别名查询
r($biTest->getFieldsWithTableTest('SELECT u.account, p.name FROM zt_user u LEFT JOIN zt_product p ON u.id = p.id')) && p('account,name') && e('zt_user,zt_product'); // 步骤3：多表连接查询
r($biTest->getFieldsWithTableTest('SELECT u.account AS user_account, u.realname AS user_name FROM zt_user u')) && p('user_account,user_name') && e('zt_user,zt_user'); // 步骤4：带列别名查询
r($biTest->getFieldsWithTableTest('INVALID SQL STATEMENT')) && p() && e('0'); // 步骤5：无效SQL处理
r($biTest->getFieldsWithTableTest('SELECT * FROM zt_user')) && p('account,avatar,birthday') && e('zt_user,zt_user,zt_user'); // 步骤6：通配符查询
r($biTest->getFieldsWithTableTest('SELECT u.*, p.name FROM zt_user u INNER JOIN zt_product p ON u.id = p.createdBy')) && p('account,name') && e('zt_user,zt_product'); // 步骤7：复杂JOIN查询