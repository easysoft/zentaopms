#!/usr/bin/env php
<?php

/**

title=测试 projectTao::doStart();
timeout=0
cid=17900

- 步骤1：正常情况 @1
- 步骤2：边界值 @1
- 步骤3：异常输入 @Array
- 步骤4：权限验证 @(
- 步骤5：业务规则 @[realBegan] => Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->loadYaml('project_dostart', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常启动项目
$project1 = new stdclass();
$project1->status = 'doing';
$project1->realBegan = helper::today();
$project1->comment = '';
r($projectTest->doStartTest(1, $project1)) && p() && e('1'); // 步骤1：正常情况

// 测试步骤2：启动不存在的项目
$project2 = new stdclass();
$project2->status = 'doing';
$project2->realBegan = helper::today();
$project2->comment = '';
r($projectTest->doStartTest(999, $project2)) && p() && e('1'); // 步骤2：边界值

// 测试步骤3：使用有效项目数据启动（realBegan为空字符串）
$project3 = new stdclass();
$project3->status = 'doing';
$project3->realBegan = '';
$project3->comment = '';
r($projectTest->doStartTest(1, $project3)) && p() && e('Array'); // 步骤3：异常输入

// 测试步骤4：启动项目时实际开始日期超过今天  
$project4 = new stdclass();
$project4->status = 'doing';
$project4->realBegan = date('Y-m-d', strtotime('+1 day'));
$project4->comment = '';
r($projectTest->doStartTest(2, $project4)) && p() && e('('); // 步骤4：权限验证

// 测试步骤5：使用无效的项目ID（负数）
$project5 = new stdclass();
$project5->status = 'doing';
$project5->realBegan = helper::today();
$project5->comment = '';
r($projectTest->doStartTest(-1, $project5)) && p() && e('[realBegan] => Array'); // 步骤5：业务规则