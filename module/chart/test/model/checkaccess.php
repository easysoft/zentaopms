#!/usr/bin/env php
<?php

/**

title=测试 chartModel::checkAccess();
timeout=0
cid=0

- 步骤1：管理员访问开放图表，应该有权限 @0
- 步骤2：管理员访问私有图表，应该有权限 @0
- 步骤3：用户访问自己创建的开放图表，应该有权限 @0
- 步骤4：用户访问白名单中的私有图表，应该有权限 @0
- 步骤5：用户无权限访问私有图表，应该被拒绝 @access_denied

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 准备测试数据
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,test1,test2,user1,user2');
$userTable->password->range('123456');
$userTable->realname->range('管理员,测试1,测试2,用户1,用户2');
$userTable->admin->range('super,no,no,no,no');
$userTable->role->range(',,,,');
$userTable->deleted->range('0');
$userTable->gen(5);

$chartTable = zenData('chart');
$chartTable->id->range('1-10');
$chartTable->name->range('开放图表,私有图表,用户图表,白名单图表,管理员图表');
$chartTable->dimension->range('0');
$chartTable->type->range('card');
$chartTable->acl->range('open,private,open,private,open');
$chartTable->whitelist->range(',,"","test2",""');
$chartTable->createdBy->range('admin,test1,user1,test2,admin');
$chartTable->stage->range('published');
$chartTable->builtin->range('0');
$chartTable->deleted->range('0');
$chartTable->gen(5);

su('admin');
$chartTest = new chartTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($chartTest->checkAccessTest(1, 'preview')) && p() && e('0'); // 步骤1：管理员访问开放图表，应该有权限
r($chartTest->checkAccessTest(2, 'preview')) && p() && e('0'); // 步骤2：管理员访问私有图表，应该有权限
su('test1');
r($chartTest->checkAccessTest(3, 'edit')) && p() && e('0'); // 步骤3：用户访问自己创建的开放图表，应该有权限
su('test2');
r($chartTest->checkAccessTest(4, 'preview')) && p() && e('0'); // 步骤4：用户访问白名单中的私有图表，应该有权限
su('user1');
r($chartTest->checkAccessTest(2, 'preview')) && p() && e('access_denied'); // 步骤5：用户无权限访问私有图表，应该被拒绝