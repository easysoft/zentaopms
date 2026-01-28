#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::setPercentage();
timeout=0
cid=17433

- 执行$result1['col1']['percentage'][1] @500
- 执行$result2['col1']['percentage'][1] @400
- 执行$result3['col1']['percentage'][1] @500
- 执行$result4 @0
- 执行$result5['col1']['value'] @100

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 测试row模式百分比计算
$rowDataRow = array(
    'col1' => array(
        'value' => 100,
        'percentage' => array(0, 0, 'row', 0, 'total')
    )
);
$rowTotalRow = array('total' => 500);
$columnTotalRow = array('col1' => array('value' => 200));
$result1 = $pivotTest->setPercentageTest($rowDataRow, $rowTotalRow, $columnTotalRow);
r($result1['col1']['percentage'][1]) && p() && e('500');

// 步骤2：边界值 - 测试column模式百分比计算
$rowDataColumn = array(
    'col1' => array(
        'value' => 100,
        'percentage' => array(0, 0, 'column', 0, 'total')
    )
);
$rowTotalColumn = array('total' => 300);
$columnTotalColumn = array('col1' => array('value' => 400));
$result2 = $pivotTest->setPercentageTest($rowDataColumn, $rowTotalColumn, $columnTotalColumn);
r($result2['col1']['percentage'][1]) && p() && e('400');

// 步骤3：异常输入 - 测试total模式百分比计算
$rowDataTotal = array(
    'col1' => array(
        'value' => 150,
        'percentage' => array(0, 0, 'total', 0, 'sum')
    )
);
$rowTotalTotal = array();
$columnTotalTotal = array(
    'col1' => array('value' => 200, 'percentage' => array(0, 0, 'total', 0, 'sum')),
    'col2' => array('value' => 300, 'percentage' => array(0, 0, 'total', 0, 'sum'))
);
$result3 = $pivotTest->setPercentageTest($rowDataTotal, $rowTotalTotal, $columnTotalTotal);
r($result3['col1']['percentage'][1]) && p() && e('500');

// 步骤4：权限验证 - 测试边界值（空row数组）
$emptyRow = array();
$result4 = $pivotTest->setPercentageTest($emptyRow, array(), array());
r(count($result4)) && p() && e('0');

// 步骤5：业务规则 - 测试无percentage属性的cell
$noPercentageRow = array(
    'col1' => array('value' => 100),
    'col2' => array('value' => 200)
);
$result5 = $pivotTest->setPercentageTest($noPercentageRow, array('total' => 300), array('col1' => array('value' => 400)));
r($result5['col1']['value']) && p() && e('100');