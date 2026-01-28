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

// 准备测试数据
$table = zenData('repo');
$table->id->range('1-10');
$table->product->range('1,2,1,2,1');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->serviceHost->range('1,1,2,3,1');
$table->serviceProject->range('100,200,300,400,500');
$table->deleted->range('0');
$table->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：正常查询存在版本库的服务器ID为1
r(count($repoTest->getImportedProjectsTest(1))) && p() && e('3'); // 期望返回3个项目

// 测试步骤2：查询不存在版本库的服务器ID
r(count($repoTest->getImportedProjectsTest(999))) && p() && e('0'); // 期望返回空数组

// 测试步骤3：边界值测试服务器ID为0
r(count($repoTest->getImportedProjectsTest(0))) && p() && e('0'); // 期望返回空数组

// 测试步骤4：负数服务器ID测试
r(count($repoTest->getImportedProjectsTest(-1))) && p() && e('0'); // 期望返回空数组

// 测试步骤5：超大服务器ID测试
r(count($repoTest->getImportedProjectsTest(999999))) && p() && e('0'); // 期望返回空数组