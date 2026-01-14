#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::verifySqlWithModify();
timeout=0
cid=15961

- 步骤1：有效SELECT语句 @1
- 步骤2：空字符串属性result @fail
- 步骤3：多条SQL语句
 - 属性result @fail
 - 属性message @只能输入一条SQL语句
- 步骤4：INSERT语句
 - 属性result @fail
 - 属性message @只允许SELECT查询
- 步骤5：UPDATE语句
 - 属性result @fail
 - 属性message @只允许SELECT查询

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$dataviewTest = new dataviewModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($dataviewTest->verifySqlWithModifyTest('SELECT * FROM zt_user')) && p() && e('1'); // 步骤1：有效SELECT语句
r($dataviewTest->verifySqlWithModifyTest('')) && p('result') && e('fail'); // 步骤2：空字符串
r($dataviewTest->verifySqlWithModifyTest('SELECT * FROM zt_user; SELECT * FROM zt_product;')) && p('result,message') && e('fail,只能输入一条SQL语句'); // 步骤3：多条SQL语句
r($dataviewTest->verifySqlWithModifyTest('INSERT INTO zt_user (account) VALUES ("test")')) && p('result,message') && e('fail,只允许SELECT查询'); // 步骤4：INSERT语句
r($dataviewTest->verifySqlWithModifyTest('UPDATE zt_user SET account = "test" WHERE id = 1')) && p('result,message') && e('fail,只允许SELECT查询'); // 步骤5：UPDATE语句