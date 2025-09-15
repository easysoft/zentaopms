#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraIssue();
timeout=0
cid=0

- 步骤1：空数据列表正常处理 @true
- 步骤2：单个有效issue数据 @true
- 步骤3：无效project的issue数据 @true
- 步骤4：多个issue数据处理 @true
- 步骤5：包含完整字段的issue数据 @true

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备
// 暂时不创建数据表，直接测试方法逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例
$convertTest = new convertTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($convertTest->importJiraIssueTest(array())) && p() && e('true'); // 步骤1：空数据列表正常处理
r($convertTest->importJiraIssueTest(array((object)array('id' => '1001', 'project' => '10001', 'issuetype' => '10000', 'issuenum' => '1')))) && p() && e('true'); // 步骤2：单个有效issue数据
r($convertTest->importJiraIssueTest(array((object)array('id' => '1002', 'project' => '99999', 'issuetype' => '10000', 'issuenum' => '2')))) && p() && e('true'); // 步骤3：无效project的issue数据
r($convertTest->importJiraIssueTest(array((object)array('id' => '1003', 'project' => '10002', 'issuetype' => '10001', 'issuenum' => '3'), (object)array('id' => '1004', 'project' => '10003', 'issuetype' => '10002', 'issuenum' => '4')))) && p() && e('true'); // 步骤4：多个issue数据处理
r($convertTest->importJiraIssueTest(array((object)array('id' => '1005', 'project' => '10001', 'issuetype' => '10000', 'issuenum' => '5', 'summary' => 'Test Issue', 'description' => 'Test Description')))) && p() && e('true'); // 步骤5：包含完整字段的issue数据