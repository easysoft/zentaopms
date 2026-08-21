#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getImportedProjects();
timeout=0
cid=18066

- 期望返回3个项目 @3
- 期望返回空数组 @0
- 期望返回空数组 @0
- 期望返回空数组 @0
- 期望返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1{5}');
$repo->product->range('1,2,1,2,1');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->scmType->range('git{5}');
$repo->gitUID->range('imported-project-gituid-1,imported-project-gituid-2,imported-project-gituid-3,imported-project-gituid-4,imported-project-gituid-5');
$repo->providerID->range('1,1,2,3,1');
$repo->connector->range('`{"projectID":"100"}`,`{"projectID":"200"}`,`{"projectID":"300"}`,`{"projectID":"400"}`,`{"projectID":"500"}`');
$repo->status->range('active,active,active,importing,active');
$repo->deleted->range('0{5}');
$repo->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：正常查询存在版本库的服务器ID为1
r($repoTest->getImportedProjectsCountTest(1)) && p() && e('3'); // 期望返回3个项目

// 测试步骤2：查询不存在版本库的服务器ID
r($repoTest->getImportedProjectsCountTest(999)) && p() && e('0'); // 期望返回空数组

// 测试步骤3：边界值测试服务器ID为0
r($repoTest->getImportedProjectsCountTest(0)) && p() && e('0'); // 期望返回空数组

// 测试步骤4：负数服务器ID测试
r($repoTest->getImportedProjectsCountTest(-1)) && p() && e('0'); // 期望返回空数组

// 测试步骤5：超大服务器ID测试
r($repoTest->getImportedProjectsCountTest(999999)) && p() && e('0'); // 期望返回空数组
