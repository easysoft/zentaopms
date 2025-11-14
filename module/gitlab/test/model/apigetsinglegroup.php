#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSingleGroup();
timeout=0
cid=16616

- 步骤1：正常情况属性name @testGroup
- 步骤2：无效gitlabID @0
- 步骤3：无效groupID属性message @404 Group Not Found
- 步骤4：边界值gitlabID为0 @0
- 步骤5：边界值groupID为负数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->id->range('1-5');
$table->type->range('gitlab');
$table->name->range('GitLab服务器');
$table->url->range('https://gitlabdev.qc.oop.cc');
$table->account->range('root');
$table->token->range('glpat-b8Sa1pM9k9ygxMZYPN6w');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlabTest = new gitlabTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($gitlabTest->apiGetSingleGroupTest(1, 14)) && p('name') && e('testGroup'); // 步骤1：正常情况
r($gitlabTest->apiGetSingleGroupTest(999, 14)) && p() && e('0'); // 步骤2：无效gitlabID
r($gitlabTest->apiGetSingleGroupTest(1, 100001)) && p('message') && e('404 Group Not Found'); // 步骤3：无效groupID
r($gitlabTest->apiGetSingleGroupTest(0, 14)) && p() && e('0'); // 步骤4：边界值gitlabID为0
r($gitlabTest->apiGetSingleGroupTest(1, -1)) && p() && e('0'); // 步骤5：边界值groupID为负数