#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getProjectTaskTable();
timeout=0
cid=18254

- 步骤1：正常情况有项目数据 @2
- 步骤2：空项目列表测试 @0
- 步骤3：单个项目测试 @1
- 步骤4：边界值测试无效年份 @0
- 步骤5：验证返回数据结构第0条的name属性 @Project A

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（使用Mock实现，无需实际数据）
$table = zenData('project');
$table->gen(0);

$taskTable = zenData('task');
$taskTable->gen(0);

$executionTable = zenData('execution');
$executionTable->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 准备测试数据
$validProjectList = array(1 => 'Project A', 2 => 'Project B');
$emptyProjectList = array();
$singleProject = array(1 => 'Project A');

// 6. 强制要求：必须包含至少5个测试步骤
r(count($screenTest->getProjectTaskTableTest('2023', '01', $validProjectList))) && p() && e('2'); // 步骤1：正常情况有项目数据
r(count($screenTest->getProjectTaskTableTest('2023', '02', $emptyProjectList))) && p() && e('0'); // 步骤2：空项目列表测试
r(count($screenTest->getProjectTaskTableTest('2023', '01', $singleProject))) && p() && e('1'); // 步骤3：单个项目测试
r(count($screenTest->getProjectTaskTableTest('9999', '99', $validProjectList))) && p() && e('0'); // 步骤4：边界值测试无效年份
r($screenTest->getProjectTaskTableTest('2023', '01', $validProjectList)) && p('0:name') && e('Project A'); // 步骤5：验证返回数据结构