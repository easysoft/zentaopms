#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::groupRecords();
timeout=0
cid=17408

- 步骤1：单字段分组，期望返回2个分组 @2
- 步骤2：多字段分组，期望返回4个分组 @4
- 步骤3：空记录数组，期望返回0个分组 @0
- 步骤4：空分组字段，期望返回1个分组 @1
- 步骤5：单条记录分组，期望特定分组包含1条记录 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 准备测试数据 - 创建带有不同字段值的记录对象
$record1 = new stdClass();
$record1->status = 'active';
$record1->type = 'bug';
$record1->id = 1;

$record2 = new stdClass();
$record2->status = 'active';
$record2->type = 'task';
$record2->id = 2;

$record3 = new stdClass();
$record3->status = 'closed';
$record3->type = 'bug';
$record3->id = 3;

$record4 = new stdClass();
$record4->status = 'closed';
$record4->type = 'task';
$record4->id = 4;

$record5 = new stdClass();
$record5->status = 'active';
$record5->type = 'bug';
$record5->id = 5;

$records = array($record1, $record2, $record3, $record4, $record5);

r(count($pivotTest->groupRecordsTest($records, array('status')))) && p() && e('2'); // 步骤1：单字段分组，期望返回2个分组
r(count($pivotTest->groupRecordsTest($records, array('status', 'type')))) && p() && e('4'); // 步骤2：多字段分组，期望返回4个分组
r(count($pivotTest->groupRecordsTest(array(), array('status')))) && p() && e('0'); // 步骤3：空记录数组，期望返回0个分组
r(count($pivotTest->groupRecordsTest($records, array()))) && p() && e('1'); // 步骤4：空分组字段，期望返回1个分组
r(count($pivotTest->groupRecordsTest(array($record1), array('status'))['active'])) && p() && e('1'); // 步骤5：单条记录分组，期望特定分组包含1条记录