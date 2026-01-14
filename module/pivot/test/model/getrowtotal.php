#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowTotal();
timeout=0
cid=17400

- 执行pivotTest模块的getRowTotalTest方法，参数是$normalRow 属性col1 @25
- 执行pivotTest模块的getRowTotalTest方法，参数是$multiSameKeyRow 
 - 属性col1 @70
 - 属性col2 @50
- 执行pivotTest模块的getRowTotalTest方法，参数是$noPercentageRow 属性col1 @20
- 执行pivotTest模块的getRowTotalTest方法，参数是$complexRow 
 - 属性col1 @250
 - 属性col2 @450
 - 属性col3 @300
- 执行pivotTest模块的getRowTotalTest方法，参数是$emptyRow  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 测试步骤1：正常包含percentage信息的行数据
$normalRow = array(
    array('value' => 10, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 20, 'percentage' => array('', '', '', '', 'col2')),
    array('value' => 15, 'percentage' => array('', '', '', '', 'col1'))
);
r($pivotTest->getRowTotalTest($normalRow)) && p('col1') && e(25);

// 测试步骤2：包含多个相同列键的单元格
$multiSameKeyRow = array(
    array('value' => 30, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 40, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 50, 'percentage' => array('', '', '', '', 'col2'))
);
r($pivotTest->getRowTotalTest($multiSameKeyRow)) && p('col1,col2') && e('70,50');

// 测试步骤3：不包含percentage信息的单元格
$noPercentageRow = array(
    array('value' => 10),
    array('value' => 20, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 30)
);
r($pivotTest->getRowTotalTest($noPercentageRow)) && p('col1') && e(20);

// 测试步骤4：包含不同列键的复杂数据
$complexRow = array(
    array('value' => 100, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 200, 'percentage' => array('', '', '', '', 'col2')),
    array('value' => 300, 'percentage' => array('', '', '', '', 'col3')),
    array('value' => 150, 'percentage' => array('', '', '', '', 'col1')),
    array('value' => 250, 'percentage' => array('', '', '', '', 'col2'))
);
r($pivotTest->getRowTotalTest($complexRow)) && p('col1,col2,col3') && e('250,450,300');

// 测试步骤5：空行数据
$emptyRow = array();
r($pivotTest->getRowTotalTest($emptyRow)) && p() && e(0);