#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildWorklogData();
timeout=0
cid=15830

- 步骤1：正常情况
 - 属性id @1
 - 属性issueid @100
 - 属性author @testuser
 - 属性worklogbody @Test worklog content
 - 属性timeworked @3600
 - 属性created @2023-01-01 10:00:00
- 步骤2：缺少部分可选字段
 - 属性id @2
 - 属性issueid @200
 - 属性author @user2
 - 属性worklogbody @~~
 - 属性timeworked @0
- 步骤3：最小必需字段
 - 属性id @3
 - 属性issueid @300
 - 属性author @~~
 - 属性worklogbody @~~
 - 属性timeworked @0
- 步骤4：包含无效字段
 - 属性id @4
 - 属性issueid @400
 - 属性author @user4
 - 属性worklogbody @Content with invalid field
- 步骤5：边界值数据
 - 属性id @0
 - 属性issueid @0
 - 属性author @~~
 - 属性worklogbody @~~
 - 属性timeworked @0
 - 属性created @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildWorklogDataTest(array('id' => 1, 'issue' => 100, 'author' => 'testuser', 'body' => 'Test worklog content', 'timeworked' => 3600, 'created' => '2023-01-01 10:00:00'))) && p('id,issueid,author,worklogbody,timeworked,created') && e('1,100,testuser,Test worklog content,3600,2023-01-01 10:00:00'); // 步骤1：正常情况
r($convertTest->buildWorklogDataTest(array('id' => 2, 'issue' => 200, 'author' => 'user2'))) && p('id,issueid,author,worklogbody,timeworked') && e('2,200,user2,~~,0'); // 步骤2：缺少部分可选字段
r($convertTest->buildWorklogDataTest(array('id' => 3, 'issue' => 300))) && p('id,issueid,author,worklogbody,timeworked') && e('3,300,~~,~~,0'); // 步骤3：最小必需字段
r($convertTest->buildWorklogDataTest(array('id' => 4, 'issue' => 400, 'author' => 'user4', 'invalidfield' => 'invalid', 'body' => 'Content with invalid field'))) && p('id,issueid,author,worklogbody') && e('4,400,user4,Content with invalid field'); // 步骤4：包含无效字段
r($convertTest->buildWorklogDataTest(array('id' => 0, 'issue' => 0, 'author' => '', 'body' => '', 'timeworked' => 0, 'created' => null))) && p('id,issueid,author,worklogbody,timeworked,created') && e('0,0,~~,~~,0,~~'); // 步骤5：边界值数据