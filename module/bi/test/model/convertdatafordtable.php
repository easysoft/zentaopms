#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::convertDataForDtable();
timeout=0
cid=0

- 测试步骤1：正常透视表数据转换 @columns
- 测试步骤2：带切片字段的数据转换 @状态
- 测试步骤3：空数据对象处理 @columns
- 测试步骤4：包含合并单元格配置的数据 @2
- 测试步骤5：带下钻条件的数据处理 @#

*/

su('admin');

$biTest = new biTest();

// 测试步骤1：正常透视表数据转换
$normalData = new stdclass();
$normalData->cols = array();
$normalData->cols[0] = array(
    (object)array('label' => '产品名称', 'isSlice' => false),
    (object)array('label' => '需求数量', 'isSlice' => false)
);
$normalData->array = array(
    array('产品A', 10),
    array('产品B', 20)
);

r($biTest->convertDataForDtableTest($normalData, array(), '1.0', 'published')) && p('0') && e('columns');

// 测试步骤2：带切片字段的数据转换
$sliceData = new stdclass();
$sliceData->cols = array();
$sliceData->cols[0] = array(
    (object)array('label' => '产品', 'isSlice' => false),
    (object)array('label' => '状态', 'isSlice' => true, 'colspan' => 2)
);
$sliceData->cols[1] = array(
    (object)array('label' => '进行中'),
    (object)array('label' => '已完成')
);
$sliceData->array = array(
    array('产品A', 5, 3),
    array('产品B', 8, 12)
);

r($biTest->convertDataForDtableTest($sliceData, array(), '1.0', 'published')) && p('field1,headerGroup') && e('状态');

// 测试步骤3：空数据对象处理
$emptyData = new stdclass();
$emptyData->cols = array();
$emptyData->array = array();

r($biTest->convertDataForDtableTest($emptyData, array(), '1.0', 'published')) && p('0') && e('columns');

// 测试步骤4：包含合并单元格配置的数据
$mergeData = new stdclass();
$mergeData->cols = array();
$mergeData->cols[0] = array(
    (object)array('label' => '部门', 'isSlice' => false),
    (object)array('label' => '人数', 'isSlice' => false)
);
$mergeData->array = array(
    array('研发部', 50),
    array('测试部', 30)
);
$mergeConfigs = array(
    0 => array(2),  // 第一行第一列合并2行
    1 => array(1)
);

r($biTest->convertDataForDtableTest($mergeData, $mergeConfigs, '1.0', 'published')) && p('0,field0_rowspan') && e('2');

// 测试步骤5：带下钻条件的数据处理
$drillData = new stdclass();
$drillData->cols = array();
$drillData->cols[0] = array(
    (object)array('label' => '项目', 'isSlice' => false, 'isDrilling' => true, 'drillField' => 'project', 'condition' => array()),
    (object)array('label' => '任务数', 'isSlice' => false)
);
$drillData->array = array(
    array('项目A', 25),
    array('项目B', 35)
);
$drillData->drills = array();

r($biTest->convertDataForDtableTest($drillData, array(), '1.0', 'published')) && p('field0,link') && e('#');