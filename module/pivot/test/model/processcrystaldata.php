#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processCrystalData();
timeout=0
cid=17415

- 步骤1：正常情况 @2
- 步骤2：边界值 @0
- 步骤3：异常输入 @2
- 步骤4：权限验证 @1
- 步骤5：业务规则 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 包含分组和列设置
$normalGroups = array('status', 'priority');
$normalRecords = array();
$record1 = new stdClass();
$record1->id = 1;
$record1->status = 'active';
$record1->priority = 'high';
$record1->status_origin = 'active';
$record1->priority_origin = 'high';
$record1->name = 'Test Record 1';
$normalRecords[] = $record1;

$record2 = new stdClass();
$record2->id = 2;
$record2->status = 'closed';
$record2->priority = 'low';
$record2->status_origin = 'closed';
$record2->priority_origin = 'low';
$record2->name = 'Test Record 2';
$normalRecords[] = $record2;

$normalSettings = array(
    'columns' => array(
        array('field' => 'name', 'title' => 'Name'),
        array('field' => 'count', 'title' => 'Count', 'stat' => 'count')
    )
);

r(count($pivotTest->processCrystalDataTest($normalGroups, $normalRecords, $normalSettings))) && p() && e('2'); // 步骤1：正常情况

// 步骤2：边界值 - 空records数组  
$emptyRecords = array();
r(count($pivotTest->processCrystalDataTest($normalGroups, $emptyRecords, $normalSettings))) && p() && e('0'); // 步骤2：边界值

// 步骤3：无效输入 - 空columns设置
$emptyColumnsSettings = array('columns' => array());
r(count($pivotTest->processCrystalDataTest($normalGroups, $normalRecords, $emptyColumnsSettings))) && p() && e('2'); // 步骤3：异常输入

// 步骤4：复杂分组情况 - 多层分组数据
$complexGroups = array('category', 'type', 'status');
$complexRecords = array();
$record3 = new stdClass();
$record3->id = 3;
$record3->category = 'bug';
$record3->type = 'defect';
$record3->status = 'open';
$record3->category_origin = 'bug';
$record3->type_origin = 'defect';
$record3->status_origin = 'open';
$record3->title = 'Complex Record';
$complexRecords[] = $record3;

r(count($pivotTest->processCrystalDataTest($complexGroups, $complexRecords, $normalSettings))) && p() && e('1'); // 步骤4：权限验证

// 步骤5：业务规则验证 - 包含slice切片数据
$sliceSettings = array(
    'columns' => array(
        array('field' => 'assignedTo', 'title' => 'Assigned To', 'slice' => 'status'),
        array('field' => 'estimate', 'title' => 'Estimate', 'stat' => 'sum')
    )
);
$sliceRecords = array();
$record4 = new stdClass();
$record4->id = 4;
$record4->status = 'doing';
$record4->priority = 'medium';
$record4->status_origin = 'doing';
$record4->priority_origin = 'medium';
$record4->assignedTo = 'user1';
$record4->estimate = 8;
$sliceRecords[] = $record4;

r(count($pivotTest->processCrystalDataTest($normalGroups, $sliceRecords, $sliceSettings))) && p() && e('1'); // 步骤5：业务规则