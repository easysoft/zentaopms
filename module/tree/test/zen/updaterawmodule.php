#!/usr/bin/env php
<?php

/**

title=测试 treeZen::updateRawModule();
timeout=0
cid=19399

- 步骤1：测试bug视图类型 @bug
- 步骤2：测试case视图类型 @testcase
- 步骤3：测试caselib视图类型 @caselib
- 步骤4：测试datasource视图类型 @workflowdatasource
- 步骤5：测试工作流模块类型 @workflow

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$treeTest = new treeTest();

// 4. 执行测试步骤（必须包含至少5个测试步骤）
r($treeTest->updateRawModuleTest(1, 'bug')) && p() && e('bug'); // 步骤1：测试bug视图类型
r($treeTest->updateRawModuleTest(1, 'case')) && p() && e('testcase'); // 步骤2：测试case视图类型
r($treeTest->updateRawModuleTest(1, 'caselib')) && p() && e('caselib'); // 步骤3：测试caselib视图类型
r($treeTest->updateRawModuleTest(1, 'datasource')) && p() && e('workflowdatasource'); // 步骤4：测试datasource视图类型
r($treeTest->updateRawModuleTest(1, 'workflow_test')) && p() && e('workflow'); // 步骤5：测试工作流模块类型