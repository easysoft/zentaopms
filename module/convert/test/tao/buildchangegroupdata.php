#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildChangeGroupData();
timeout=0
cid=15803

- 步骤1：正常情况
 - 属性id @1
 - 属性issueid @100
 - 属性author @admin
 - 属性created @2023-01-01 10:00:00
- 步骤2：完整字段
 - 属性id @2
 - 属性issueid @200
 - 属性author @user1
 - 属性created @2023-01-02 15:30:00
- 步骤3：包含额外字段
 - 属性id @3
 - 属性issueid @300
 - 属性author @user2
 - 属性created @2023-01-03 09:15:00
- 步骤4：空值字段
 - 属性id @4
 - 属性issueid @400
- 步骤5：数字ID测试
 - 属性id @5
 - 属性issueid @500
 - 属性author @test
 - 属性created @2023-01-05 12:00:00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildChangeGroupDataTest(array('id' => '1', 'issue' => '100', 'author' => 'admin', 'created' => '2023-01-01 10:00:00'))) && p('id,issueid,author,created') && e('1,100,admin,2023-01-01 10:00:00'); // 步骤1：正常情况
r($convertTest->buildChangeGroupDataTest(array('id' => '2', 'issue' => '200', 'author' => 'user1', 'created' => '2023-01-02 15:30:00'))) && p('id,issueid,author,created') && e('2,200,user1,2023-01-02 15:30:00'); // 步骤2：完整字段
r($convertTest->buildChangeGroupDataTest(array('id' => '3', 'issue' => '300', 'author' => 'user2', 'created' => '2023-01-03 09:15:00', 'extra' => 'ignored'))) && p('id,issueid,author,created') && e('3,300,user2,2023-01-03 09:15:00'); // 步骤3：包含额外字段
r($convertTest->buildChangeGroupDataTest(array('id' => '4', 'issue' => '400', 'author' => '', 'created' => ''))) && p('id,issueid') && e('4,400'); // 步骤4：空值字段
r($convertTest->buildChangeGroupDataTest(array('id' => '5', 'issue' => '500', 'author' => 'test', 'created' => '2023-01-05 12:00:00'))) && p('id,issueid,author,created') && e('5,500,test,2023-01-05 12:00:00'); // 步骤5：数字ID测试