#!/usr/bin/env php
<?php

/**

title=测试 biModel::getObjectOptions();
timeout=0
cid=15176

- 步骤1：正常获取用户ID选项属性1 @1
- 步骤2：获取产品名称选项属性1 @正常产品
- 步骤3：不存在的对象类型 @0
- 步骤4：不存在的字段时使用id字段属性1 @1
- 步骤5：空参数测试 @0
- 步骤6：路径穿越的对象名不加载任何文件 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('testuser1,testuser2,testuser3,testuser4,testuser5,testuser6,testuser7,testuser8,testuser9,testuser10');
$userTable->realname->range('测试用户1,测试用户2,测试用户3,测试用户4,测试用户5,测试用户6,测试用户7,测试用户8,测试用户9,测试用户10');
$userTable->password->range('123456{10}');
$userTable->role->range('qa{10}');
$userTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('正常产品{5}');
$productTable->status->range('normal{5}');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getObjectOptionsTest('user', 'id')) && p('1') && e('1'); // 步骤1：正常获取用户ID选项
r($biTest->getObjectOptionsTest('product', 'name')) && p('1') && e('正常产品'); // 步骤2：获取产品名称选项
r($biTest->getObjectOptionsTest('nonexistent', 'id')) && p() && e(0); // 步骤3：不存在的对象类型
r($biTest->getObjectOptionsTest('user', 'nonexistent')) && p('1') && e('1'); // 步骤4：不存在的字段时使用id字段
r($biTest->getObjectOptionsTest('', '')) && p() && e(0); // 步骤5：空参数测试
r($biTest->getObjectOptionsTest('../../../../../tmp/vuln014_marker', 'demo') === array()) && p() && e('1'); // 步骤6：路径穿越的对象名不加载任何文件
