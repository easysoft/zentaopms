#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetProjectMembers();
timeout=0
cid=16612

- 步骤1：使用空的gitlabID和projectID @return null
- 步骤2：无效projectID @0
- 步骤3：查询所有成员 @0
- 步骤4：查询特定用户 @0
- 步骤5：测试负数ID边界值 @return null

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->type->range('gitlab');
$table->name->range('GitLab服务器{1-5}');
$table->url->range('http://gitlab.test{1-5}.com');
$table->account->range('admin{1-5}');
$table->password->range('password123{1-5}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlabTest = new gitlabTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
$result = $gitlabTest->apiGetProjectMembersTest(0, 0);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); // 步骤1：使用空的gitlabID和projectID

r($gitlabTest->apiGetProjectMembersTest(1, 0)) && p() && e('0'); // 步骤2：无效projectID

r($gitlabTest->apiGetProjectMembersTest(1, 2)) && p() && e('0'); // 步骤3：查询所有成员

r($gitlabTest->apiGetProjectMembersTest(1, 2, 4)) && p() && e('0'); // 步骤4：查询特定用户

$result = $gitlabTest->apiGetProjectMembersTest(-1, -1);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); // 步骤5：测试负数ID边界值