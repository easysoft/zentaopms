#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetMergeRequests();
timeout=0
cid=16609

- 步骤1：使用有效的GitLab ID和项目ID获取合并请求 @~~
- 步骤2：验证返回结果是数组类型 @1
- 步骤3：使用无效的GitLab ID测试错误处理 @~~
- 步骤4：使用无效的项目ID测试边界处理 @~~
- 步骤5：使用边界值项目ID为0测试参数验证 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('gitlab1,gitlab2,gitlab3,gitlab4,gitlab5');
$pipeline->type->range('gitlab{5}');
$pipeline->url->range('https://gitlab{1-5}.example.com');
$pipeline->token->range('glpat-test{1-5}');
$pipeline->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetMergeRequestsTest(1, 18)) && p() && e('~~'); // 步骤1：使用有效的GitLab ID和项目ID获取合并请求
$result = $gitlabTest->apiGetMergeRequestsTest(1, 18);
r(is_array($result)) && p() && e('1'); // 步骤2：验证返回结果是数组类型
r($gitlabTest->apiGetMergeRequestsTest(999, 18)) && p() && e('~~'); // 步骤3：使用无效的GitLab ID测试错误处理
r($gitlabTest->apiGetMergeRequestsTest(1, 999999)) && p() && e('~~'); // 步骤4：使用无效的项目ID测试边界处理
r($gitlabTest->apiGetMergeRequestsTest(1, 0)) && p() && e('~~'); // 步骤5：使用边界值项目ID为0测试参数验证