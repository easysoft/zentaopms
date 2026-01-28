#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::prepareBuilitinPivotDrillSQL();
timeout=0
cid=15196

- 步骤1：空下钻数组情况 @0
- 步骤2：单个下钻配置情况（删除+插入SQL） @2
- 步骤3：多个下钻配置情况（删除+2个插入SQL） @3
- 步骤4：复杂条件下钻配置 @2
- 步骤5：特殊字符下钻配置 @2

*/

$biTest = new biModelTest();

// 测试步骤1：空下钻数组情况
r(count($biTest->prepareBuilitinPivotDrillSQLTest(1, array(), 1))) && p() && e('0'); // 步骤1：空下钻数组情况

// 测试步骤2：单个下钻配置情况
$singleDrill = array(
    array(
        'field' => 'status',
        'object' => 'bug',
        'whereSql' => 'status = "active"',
        'condition' => array('status' => 'active')
    )
);
r(count($biTest->prepareBuilitinPivotDrillSQLTest(1, $singleDrill, 1))) && p() && e('2'); // 步骤2：单个下钻配置情况（删除+插入SQL）

// 测试步骤3：多个下钻配置情况  
$multipleDrills = array(
    array(
        'field' => 'status',
        'object' => 'bug',
        'whereSql' => 'status = "active"',
        'condition' => array('status' => 'active')
    ),
    array(
        'field' => 'pri',
        'object' => 'bug',
        'whereSql' => 'pri = "high"',
        'condition' => array('pri' => 'high')
    )
);
r(count($biTest->prepareBuilitinPivotDrillSQLTest(2, $multipleDrills, 1))) && p() && e('3'); // 步骤3：多个下钻配置情况（删除+2个插入SQL）

// 测试步骤4：复杂条件下钻配置
$complexDrill = array(
    array(
        'field' => 'assignedTo',
        'object' => 'task',
        'whereSql' => 'assignedTo IN ("user1", "user2")',
        'condition' => array('assignedTo' => array('user1', 'user2'), 'status' => 'doing')
    )
);
r(count($biTest->prepareBuilitinPivotDrillSQLTest(3, $complexDrill, 2))) && p() && e('2'); // 步骤4：复杂条件下钻配置

// 测试步骤5：特殊字符下钻配置
$specialDrill = array(
    array(
        'field' => 'title',
        'object' => 'story',
        'whereSql' => 'title LIKE "%测试%"',
        'condition' => array('title' => '测试\'特殊"字符')
    )
);
r(count($biTest->prepareBuilitinPivotDrillSQLTest(4, $specialDrill, 1))) && p() && e('2'); // 步骤5：特殊字符下钻配置