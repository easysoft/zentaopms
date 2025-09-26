#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowStatus();
timeout=0
cid=0

- 步骤1：开源版本直接返回原relations @array
- 步骤2：空relations数组测试 @array
- 步骤3：无zentaoStatus的relations测试 @array
- 步骤4：zentaoStatus键不匹配的relations测试 @array
- 步骤5：有效zentaoObject但无状态配置的relations测试 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'bug'), 'zentaoStatus1' => array('status1' => 'active')))) && p() && e('array'); // 步骤1：开源版本直接返回原relations
r($convertTest->createWorkflowStatusTest(array())) && p() && e('array'); // 步骤2：空relations数组测试
r($convertTest->createWorkflowStatusTest(array('otherKey' => array('1' => 'bug')))) && p() && e('array'); // 步骤3：无zentaoStatus的relations测试
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'bug'), 'invalidStatus' => array('status1' => 'active')))) && p() && e('array'); // 步骤4：zentaoStatus键不匹配的relations测试
r($convertTest->createWorkflowStatusTest(array('zentaoObject' => array('1' => 'bug'), 'zentaoStatus1' => array('status1' => 'normal_status')))) && p() && e('array'); // 步骤5：有效zentaoObject但无状态配置的relations测试