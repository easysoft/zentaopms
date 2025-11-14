#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSingleUser();
timeout=0
cid=16622

- 步骤1：正常查询（无真实API连接） @0
- 步骤2：无效gitlabID查询 @0
- 步骤3：不存在userID @0
- 步骤4：边界值userID为0 @0
- 步骤5：负数userID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->type->range('gitlab');
$table->name->range('GitLab测试实例');
$table->url->range('https://gitlab.example.com/api/v4%s?private_token=glpat-test');
$table->account->range('testuser');
$table->password->range('testtoken123');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlab = new gitlabTest();

// 5. 测试步骤（至少5个）
r($gitlab->apiGetSingleUserTest(1, 1)) && p() && e('0'); // 步骤1：正常查询（无真实API连接）
r($gitlab->apiGetSingleUserTest(0, 2)) && p() && e('0'); // 步骤2：无效gitlabID查询
r($gitlab->apiGetSingleUserTest(1, 100001)) && p() && e('0'); // 步骤3：不存在userID
r($gitlab->apiGetSingleUserTest(1, 0)) && p() && e('0'); // 步骤4：边界值userID为0
r($gitlab->apiGetSingleUserTest(1, -1)) && p() && e('0'); // 步骤5：负数userID