#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowStatus();
timeout=0
cid=0

- 步骤1：开源版本测试第zentaoObject条的1属性 @bug
- 步骤2：测试用例状态配置第zentaoStatus1条的jira_status1属性 @add_case_status
- 步骤3：工作流状态配置第zentaoStatus1条的jira_status2属性 @add_flow_status
- 步骤4：混合状态配置测试第zentaoStatus1条的status1属性 @add_case_status
- 步骤5：空relations数组测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于zendata对workflowfield表有问题，跳过数据生成

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'bug'), 'zentaoStatus1' => array('status1' => 'active')))) && p('zentaoObject:1') && e('bug'); // 步骤1：开源版本测试
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'testcase'), 'zentaoStatus1' => array('jira_status1' => 'add_case_status')))) && p('zentaoStatus1:jira_status1') && e('add_case_status'); // 步骤2：测试用例状态配置
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'bug'), 'zentaoStatus1' => array('jira_status2' => 'add_flow_status')))) && p('zentaoStatus1:jira_status2') && e('add_flow_status'); // 步骤3：工作流状态配置
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'story'), 'zentaoStatus1' => array('status1' => 'add_case_status', 'status2' => 'active')))) && p('zentaoStatus1:status1') && e('add_case_status'); // 步骤4：混合状态配置测试
r($convertTest->createWorkflowStatusTest(array())) && p() && e('0'); // 步骤5：空relations数组测试