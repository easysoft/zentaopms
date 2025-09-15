#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getCellData();
timeout=0
cid=0

- 步骤1：showOrigin=1返回原始数据isGroup属性isGroup @0
- 步骤2：sum统计属性value @35
- 步骤3：count统计属性value @3
- 步骤4：max统计属性value @20
- 步骤5：min统计属性value @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 准备测试数据
$records = array();
$record1 = new stdClass();
$record1->score = 10;
$records[] = $record1;

$record2 = new stdClass();
$record2->score = 20;
$records[] = $record2;

$record3 = new stdClass();
$record3->score = 5;
$records[] = $record3;

// 5. 🔴 强制要求：必须包含至少5个测试步骤  
r($pivotTest->getCellDataTest('col1', $records, array('field' => 'score', 'showOrigin' => 1))) && p('isGroup') && e('0'); // 步骤1：showOrigin=1返回原始数据
r($pivotTest->getCellDataTest('col2', $records, array('field' => 'score', 'stat' => 'sum'))) && p('value') && e('35'); // 步骤2：sum统计
r($pivotTest->getCellDataTest('col3', $records, array('field' => 'score', 'stat' => 'count'))) && p('value') && e('3'); // 步骤3：count统计
r($pivotTest->getCellDataTest('col4', $records, array('field' => 'score', 'stat' => 'max'))) && p('value') && e('20'); // 步骤4：max统计
r($pivotTest->getCellDataTest('col5', $records, array('field' => 'score', 'stat' => 'min'))) && p('value') && e('5'); // 步骤5：min统计