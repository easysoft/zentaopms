#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getGroupsKey();
timeout=0
cid=17389

- 执行pivotTest模块的getGroupsKeyTest方法，参数是$groups1, $record1  @bug_active_high
- 执行pivotTest模块的getGroupsKeyTest方法，参数是$groups2, $record2  @story_product_development
- 执行pivotTest模块的getGroupsKeyTest方法，参数是$groups3, $record3  @admin
- 执行pivotTest模块的getGroupsKeyTest方法，参数是$groups4, $record4  @0
- 执行pivotTest模块的getGroupsKeyTest方法，参数是$groups5, $record5  @dev_developer_3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常标量字段组合
$record1 = new stdClass();
$record1->category = 'bug';
$record1->status = 'active';
$record1->priority = 'high';
$groups1 = array('category', 'status', 'priority');
r($pivotTest->getGroupsKeyTest($groups1, $record1)) && p() && e('bug_active_high');

// 步骤2：包含数组字段的组合
$record2 = new stdClass();
$record2->type = 'story';
$record2->module = array('value' => 'product', 'text' => '产品模块');
$record2->stage = 'development';
$groups2 = array('type', 'module', 'stage');
r($pivotTest->getGroupsKeyTest($groups2, $record2)) && p() && e('story_product_development');

// 步骤3：单个字段组合
$record3 = new stdClass();
$record3->owner = 'admin';
$groups3 = array('owner');
r($pivotTest->getGroupsKeyTest($groups3, $record3)) && p() && e('admin');

// 步骤4：空组字段数组
$record4 = new stdClass();
$record4->field1 = 'value1';
$groups4 = array();
r($pivotTest->getGroupsKeyTest($groups4, $record4)) && p() && e('0');

// 步骤5：混合标量和数组字段
$record5 = new stdClass();
$record5->dept = 'dev';
$record5->role = array('value' => 'developer', 'text' => '开发人员');
$record5->level = '3';
$groups5 = array('dept', 'role', 'level');
r($pivotTest->getGroupsKeyTest($groups5, $record5)) && p() && e('dev_developer_3');