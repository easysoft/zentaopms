#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getSliceRecords();
timeout=0
cid=17402

- 步骤1：正常分组 - 验证active组第一个记录的名称 @Task 1
- 步骤2：单个分组 - 验证done组包含2个记录 @2
- 步骤3：空记录数组 - 验证返回空数组 @0
- 步骤4：按status字段分组 - 验证返回2个分组 @2
- 步骤5：按category字段分组 - 验证bug组包含2个记录 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 准备测试数据
// 测试用例1：正常分组数据
$records1 = array();
$record1 = new stdClass();
$record1->id = 1;
$record1->status = 'active';
$record1->name = 'Task 1';
$records1[] = $record1;

$record2 = new stdClass();
$record2->id = 2;
$record2->status = 'closed';
$record2->name = 'Task 2';
$records1[] = $record2;

$record3 = new stdClass();
$record3->id = 3;
$record3->status = 'active';
$record3->name = 'Task 3';
$records1[] = $record3;

// 测试用例2：相同状态的记录
$records2 = array();
$record4 = new stdClass();
$record4->id = 4;
$record4->status = 'done';
$record4->name = 'Task 4';
$records2[] = $record4;

$record5 = new stdClass();
$record5->id = 5;
$record5->status = 'done';
$record5->name = 'Task 5';
$records2[] = $record5;

// 测试用例3：空记录数组
$records3 = array();

// 测试用例4：唯一ID分组
$records4 = array();
$record6 = new stdClass();
$record6->id = 6;
$record6->status = 'wait';
$record6->name = 'Task 6';
$records4[] = $record6;

$record7 = new stdClass();
$record7->id = 7;
$record7->status = 'doing';
$record7->name = 'Task 7';
$records4[] = $record7;

// 测试用例5：多分类分组
$records5 = array();
$record8 = new stdClass();
$record8->id = 8;
$record8->category = 'bug';
$record8->name = 'Bug 1';
$records5[] = $record8;

$record9 = new stdClass();
$record9->id = 9;
$record9->category = 'story';
$record9->name = 'Story 1';
$records5[] = $record9;

$record10 = new stdClass();
$record10->id = 10;
$record10->category = 'task';
$record10->name = 'Task 10';
$records5[] = $record10;

$record11 = new stdClass();
$record11->id = 11;
$record11->category = 'bug';
$record11->name = 'Bug 2';
$records5[] = $record11;

r($pivotTest->getSliceRecordsTest($records1, 'status')['active'][0]->name) && p() && e('Task 1'); // 步骤1：正常分组 - 验证active组第一个记录的名称
r(count($pivotTest->getSliceRecordsTest($records2, 'status')['done'])) && p() && e('2'); // 步骤2：单个分组 - 验证done组包含2个记录
r(count($pivotTest->getSliceRecordsTest($records3, 'status'))) && p() && e('0'); // 步骤3：空记录数组 - 验证返回空数组
r(count($pivotTest->getSliceRecordsTest($records4, 'status'))) && p() && e('2'); // 步骤4：按status字段分组 - 验证返回2个分组
r(count($pivotTest->getSliceRecordsTest($records5, 'category')['bug'])) && p() && e('2'); // 步骤5：按category字段分组 - 验证bug组包含2个记录