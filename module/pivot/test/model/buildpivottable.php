#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::buildPivotTable();
timeout=0
cid=17357

- 执行pivotTest模块的buildPivotTableTest方法，参数是$emptyData, array  @1
- 执行pivotTest模块的buildPivotTableTest方法，参数是$standardData, array  @1
- 执行pivotTest模块的buildPivotTableTest方法，参数是$configData, $configs), '项目') !== false  @1
- 执行pivotTest模块的buildPivotTableTest方法，参数是$userData, array  @1
- 执行pivotTest模块的buildPivotTableTest方法，参数是$totalData, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 准备测试数据1：空数据对象
$emptyData = new stdClass();
$emptyData->cols = array(array());
$emptyData->array = array();

// 准备测试数据2：标准数据对象
$standardData = new stdClass();
$standardData->cols = array(
    array(
        (object)array('label' => '产品', 'isGroup' => true),
        (object)array('label' => '总计', 'colspan' => 1)
    )
);
$standardData->array = array(
    array('产品A' => '产品A', 'total' => 10),
    array('产品B' => '产品B', 'total' => 20)
);
$standardData->groups = array('product');

// 准备测试数据3：带配置的数据对象
$configData = new stdClass();
$configData->cols = array(
    array(
        (object)array('label' => '项目', 'isGroup' => true),
        (object)array('label' => 'Bug数量', 'colspan' => 1)
    )
);
$configData->array = array(
    array('project' => '项目1', 'bugs' => 5),
    array('project' => '项目2', 'bugs' => 8)
);
$configData->groups = array('project');
$configs = array(
    array(1, 1),
    array(1, 1)
);

// 准备测试数据4：包含用户字段的数据对象
$userData = new stdClass();
$userData->cols = array(
    array(
        (object)array('label' => '指派给', 'name' => 'assignedTo', 'isGroup' => true),
        (object)array('label' => '任务数', 'colspan' => 1)
    )
);
$userData->array = array(
    array('assignedTo' => 'admin', 'count' => 3),
    array('assignedTo' => 'user1', 'count' => 2)
);
$userData->groups = array('assignedTo');

// 准备测试数据5：包含总计的数据对象
$totalData = new stdClass();
$totalData->cols = array(
    array(
        (object)array('label' => '状态', 'isGroup' => true),
        (object)array('label' => '数量', 'colspan' => 1)
    )
);
$totalData->array = array(
    array('status' => '进行中', 'count' => 10),
    array('status' => '已完成', 'count' => 15),
    array('status' => '总计', 'count' => 25)
);
$totalData->groups = array('status');
$totalData->showAllTotal = true;

r(strpos($pivotTest->buildPivotTableTest($emptyData, array()), '<div class=\'reportData\'><table') !== false) && p() && e(1);
r(strpos($pivotTest->buildPivotTableTest($standardData, array()), '产品') !== false) && p() && e(1);
r(strpos($pivotTest->buildPivotTableTest($configData, $configs), '项目') !== false) && p() && e(1);
r(strpos($pivotTest->buildPivotTableTest($userData, array()), '指派给') !== false) && p() && e(1);
r(strpos($pivotTest->buildPivotTableTest($totalData, array()), '总计') !== false) && p() && e(1);