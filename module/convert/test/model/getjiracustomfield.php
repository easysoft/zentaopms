#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraCustomField();
timeout=0
cid=0

- 步骤1：开源版返回空数组 @0
- 步骤2：zentaoObject为空返回空数组 @0
- 步骤3：step不在zentaoObject keys中返回空数组 @0
- 步骤4：正常情况获取自定义字段 @0
- 步骤5：获取自定义字段数量验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getJiraCustomFieldTest(1, array())) && p() && e('0'); // 步骤1：开源版返回空数组
r($convertTest->getJiraCustomFieldTest(1, array('zentaoObject' => array()))) && p() && e('0'); // 步骤2：zentaoObject为空返回空数组
r($convertTest->getJiraCustomFieldTest(5, array('zentaoObject' => array(1 => 'story', 2 => 'task')))) && p() && e('0'); // 步骤3：step不在zentaoObject keys中返回空数组
r($convertTest->getJiraCustomFieldTest(1, array('zentaoObject' => array(1 => 'story', 2 => 'task')))) && p() && e('0'); // 步骤4：正常情况获取自定义字段
r(count($convertTest->getJiraCustomFieldTest(1, array('zentaoObject' => array(1 => 'story', 2 => 'task'))))) && p() && e('0'); // 步骤5：获取自定义字段数量验证