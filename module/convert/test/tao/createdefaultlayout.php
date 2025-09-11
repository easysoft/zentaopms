#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultLayout();
timeout=0
cid=0

- 执行convertTest模块的createDefaultLayoutTest方法，参数是$fields1, $flow1, 0  @0
- 执行convertTest模块的createDefaultLayoutTest方法，参数是$fields2, $flow2, 0  @0
- 执行convertTest模块的createDefaultLayoutTest方法，参数是$fields3, $flow3, 0  @0
- 执行convertTest模块的createDefaultLayoutTest方法，参数是$fields4, $flow4, 1  @0
- 执行convertTest模块的createDefaultLayoutTest方法，参数是$fields5, $flow5, 2  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('workflowlayout')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：普通字段正常布局创建
$fields1 = array();
$field1 = new stdClass();
$field1->field = 'title';
$field2 = new stdClass();
$field2->field = 'description';
$fields1[] = $field1;
$fields1[] = $field2;

$flow1 = new stdClass();
$flow1->module = 'test';

r($convertTest->createDefaultLayoutTest($fields1, $flow1, 0)) && p() && e('0');

// 测试步骤2：包含deleted字段的字段列表
$fields2 = array();
$field3 = new stdClass();
$field3->field = 'title';
$field4 = new stdClass();
$field4->field = 'deleted';
$fields2[] = $field3;
$fields2[] = $field4;

$flow2 = new stdClass();
$flow2->module = 'test';

r($convertTest->createDefaultLayoutTest($fields2, $flow2, 0)) && p() && e('0');

// 测试步骤3：feedback模块view动作转换为adminview
$fields3 = array();
$field5 = new stdClass();
$field5->field = 'title';
$fields3[] = $field5;

$flow3 = new stdClass();
$flow3->module = 'feedback';

r($convertTest->createDefaultLayoutTest($fields3, $flow3, 0)) && p() && e('0');

// 测试步骤4：create/edit动作过滤系统字段
$fields4 = array();
$field6 = new stdClass();
$field6->field = 'id';
$field7 = new stdClass();
$field7->field = 'createdBy';
$field8 = new stdClass();
$field8->field = 'title';
$fields4[] = $field6;
$fields4[] = $field7;
$fields4[] = $field8;

$flow4 = new stdClass();
$flow4->module = 'issue';

r($convertTest->createDefaultLayoutTest($fields4, $flow4, 1)) && p() && e('0');

// 测试步骤5：browse动作添加actions字段
$fields5 = array();
$field9 = new stdClass();
$field9->field = 'title';
$field10 = new stdClass();
$field10->field = 'status';
$fields5[] = $field9;
$fields5[] = $field10;

$flow5 = new stdClass();
$flow5->module = 'task';

r($convertTest->createDefaultLayoutTest($fields5, $flow5, 2)) && p() && e('0');