#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultLayout();
timeout=0
cid=15836

- 步骤1:正常字段和普通模块 @1
- 步骤2:包含deleted字段应被过滤 @1
- 步骤3:feedback模块view动作转换为adminview @1
- 步骤4:create动作过滤特殊字段 @1
- 步骤5:不同分组的布局创建 @1
- 步骤6:包含多个字段的正常情况 @1
- 步骤7:空字段数组测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $tester;

/* Clean up workflowlayout table before testing. */
$tester->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('1=1')->exec();

su('admin');

$convertTest = new convertTaoTest();

// 步骤1: 正常字段和普通模块
$field1 = new stdClass();
$field1->field = 'title';
$field2 = new stdClass();
$field2->field = 'status';
$flow1 = new stdClass();
$flow1->module = 'story';
r($convertTest->createDefaultLayoutTest(array($field1, $field2), $flow1, 0)) && p() && e('1'); // 步骤1:正常字段和普通模块

// 步骤2: 包含deleted字段应被过滤
$field3 = new stdClass();
$field3->field = 'deleted';
$field4 = new stdClass();
$field4->field = 'name';
$flow2 = new stdClass();
$flow2->module = 'task';
r($convertTest->createDefaultLayoutTest(array($field3, $field4), $flow2, 0)) && p() && e('1'); // 步骤2:包含deleted字段应被过滤

// 步骤3: feedback模块view动作转换为adminview
$field5 = new stdClass();
$field5->field = 'title';
$flow3 = new stdClass();
$flow3->module = 'feedback';
r($convertTest->createDefaultLayoutTest(array($field5), $flow3, 0)) && p() && e('1'); // 步骤3:feedback模块view动作转换为adminview

// 步骤4: create动作应过滤特殊字段(id, parent, createdBy等)
$field6 = new stdClass();
$field6->field = 'id';
$field7 = new stdClass();
$field7->field = 'createdBy';
$field8 = new stdClass();
$field8->field = 'title';
$flow4 = new stdClass();
$flow4->module = 'bug';
r($convertTest->createDefaultLayoutTest(array($field6, $field7, $field8), $flow4, 0)) && p() && e('1'); // 步骤4:create动作过滤特殊字段

// 步骤5: 不同分组的布局创建
$field9 = new stdClass();
$field9->field = 'priority';
$flow5 = new stdClass();
$flow5->module = 'product';
r($convertTest->createDefaultLayoutTest(array($field9), $flow5, 5)) && p() && e('1'); // 步骤5:不同分组的布局创建

// 步骤6: 包含多个字段的正常情况
$field10 = new stdClass();
$field10->field = 'name';
$field11 = new stdClass();
$field11->field = 'description';
$field12 = new stdClass();
$field12->field = 'type';
$flow6 = new stdClass();
$flow6->module = 'project';
r($convertTest->createDefaultLayoutTest(array($field10, $field11, $field12), $flow6, 0)) && p() && e('1'); // 步骤6:包含多个字段的正常情况

// 步骤7: 空字段数组测试
$flow7 = new stdClass();
$flow7->module = 'testcase';
r($convertTest->createDefaultLayoutTest(array(), $flow7, 0)) && p() && e('1'); // 步骤7:空字段数组测试