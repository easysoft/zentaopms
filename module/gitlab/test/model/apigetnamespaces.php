#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetNamespaces();
timeout=0
cid=16610

- 步骤1：有效GitLab ID获取namespace列表 @~~
- 步骤2：有效GitLab ID检查返回数据类型 @1
- 步骤3：无效GitLab ID（0）获取namespace @~~
- 步骤4：不存在的GitLab ID获取namespace @~~
- 步骤5：负数GitLab ID获取namespace @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('gitlab1,gitlab2,gitlab3,gitlab4,gitlab5');
$pipeline->type->range('gitlab{5}');
$pipeline->url->range('https://gitlabdev.qc.oop.cc{5}');
$pipeline->token->range('glpat-b8Sa1pM9k9ygxMZYPN6w{5}');
$pipeline->gen(5);

zenData('oauth')->gen(4);

// 用户登录
su('admin');

// 创建测试实例
$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetNamespacesTest(1)) && p() && e('~~'); // 步骤1：有效GitLab ID获取namespace列表
$result = $gitlabTest->apiGetNamespacesTest(1);
r(is_array($result)) && p() && e('1'); // 步骤2：有效GitLab ID检查返回数据类型
r($gitlabTest->apiGetNamespacesTest(0)) && p() && e('~~'); // 步骤3：无效GitLab ID（0）获取namespace
r($gitlabTest->apiGetNamespacesTest(999)) && p() && e('~~'); // 步骤4：不存在的GitLab ID获取namespace
r($gitlabTest->apiGetNamespacesTest(-1)) && p() && e('~~'); // 步骤5：负数GitLab ID获取namespace