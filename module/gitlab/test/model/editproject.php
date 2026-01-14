#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::editProject();
timeout=0
cid=16645

- 执行gitlabTest模块的editProjectTest方法，参数是$gitlabID, $emptyNameProject 第name条的0属性 @项目名称不能为空
- 执行gitlabTest模块的editProjectTest方法，参数是$gitlabID, $validProject  @1
- 执行gitlabTest模块的editProjectTest方法，参数是$invalidGitlabID, $testProject  @0
- 执行gitlabTest模块的editProjectTest方法，参数是$gitlabID, $incompleteProject 第name条的0属性 @项目名称不能为空
- 执行gitlabTest模块的editProjectTest方法，参数是$gitlabID, $fullProject  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('pipeline')->gen(5);
zenData('action')->gen(0);

// 用户登录
su('admin');

// 创建测试实例
$gitlabTest = new gitlabModelTest();

$gitlabID = 1;
$validProjectID = 100;

// 测试步骤1：项目名称为空的情况
$emptyNameProject = new stdclass();
$emptyNameProject->description = 'test description';
r($gitlabTest->editProjectTest($gitlabID, $emptyNameProject)) && p('name:0') && e('项目名称不能为空');

// 测试步骤2：正常更新项目信息（模拟成功）
$validProject = new stdclass();
$validProject->name = 'valid_project_name';
$validProject->id = $validProjectID;
$validProject->description = 'updated description';
r($gitlabTest->editProjectTest($gitlabID, $validProject)) && p() && e('1');

// 测试步骤3：使用无效的gitlabID（模拟API错误）
$invalidGitlabID = 999;
$testProject = new stdclass();
$testProject->name = 'test_project';
$testProject->id = $validProjectID;
r($gitlabTest->editProjectTest($invalidGitlabID, $testProject)) && p() && e('0');

// 测试步骤4：项目对象缺少必要属性
$incompleteProject = new stdclass();
$incompleteProject->description = 'only description';
r($gitlabTest->editProjectTest($gitlabID, $incompleteProject)) && p('name:0') && e('项目名称不能为空');

// 测试步骤5：更新项目名称和描述（完整属性）
$fullProject = new stdclass();
$fullProject->name = 'full_project_test';
$fullProject->id = $validProjectID;
$fullProject->description = 'comprehensive test project';
$fullProject->visibility = 'private';
r($gitlabTest->editProjectTest($gitlabID, $fullProject)) && p() && e('1');