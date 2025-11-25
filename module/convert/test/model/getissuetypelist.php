#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getIssueTypeList();
timeout=0
cid=15771

- 步骤1：正常情况 @0
- 步骤2：空参数 @0
- 步骤3：缺少zentaoObject @0
- 步骤4：zentaoObject为空 @0
- 步骤5：无效数据 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（使用现有表结构进行模拟）

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTest();

// 5. 执行测试步骤 - 必须包含至少5个测试步骤
r($convertTest->getIssueTypeListTest(array('zentaoObject' => array('3001' => 'story', '3002' => 'task', '3003' => 'bug')))) && p() && e('0'); // 步骤1：正常情况
r($convertTest->getIssueTypeListTest(array())) && p() && e('0'); // 步骤2：空参数
r($convertTest->getIssueTypeListTest(array('invalidField' => array()))) && p() && e('0'); // 步骤3：缺少zentaoObject
r($convertTest->getIssueTypeListTest(array('zentaoObject' => array()))) && p() && e('0'); // 步骤4：zentaoObject为空
r($convertTest->getIssueTypeListTest(array('zentaoObject' => array('invalid' => 'story')))) && p() && e('0'); // 步骤5：无效数据