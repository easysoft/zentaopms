#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowSpanConfig();
timeout=0
cid=0

- 空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 步骤1：测试空记录数组处理
r($pivotTest->getRowSpanConfigTest(array())) && p() && e('0'); // 空数组

// 步骤2：测试单个记录无数组值的情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'test1', 'rowSpan' => 2),
        array('value' => 'test2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1') && e('2,1'); // 单条记录rowSpan配置

// 步骤3：测试单个记录包含数组值的情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('item1', 'item2', 'item3'), 'rowSpan' => 2),
        array('value' => 'normal', 'rowSpan' => 1)
    )
))) && p('0:0,1:0,2:0') && e('2,2,2'); // 根据数组长度生成3行，每行rowSpan为2

// 步骤4：测试多个记录无数组值的情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'row1col1', 'rowSpan' => 1),
        array('value' => 'row1col2', 'rowSpan' => 2)
    ),
    array(
        array('value' => 'row2col1', 'rowSpan' => 3),
        array('value' => 'row2col2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1') && e('1,2,3,1'); // 两条记录各自的rowSpan配置

// 步骤5：测试多个记录包含数组值的情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'normal1', 'rowSpan' => 1),
        array('value' => 'normal2', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('a', 'b'), 'rowSpan' => 3),
        array('value' => 'normal3', 'rowSpan' => 1)
    )
))) && p('0:0,1:0,2:0') && e('1,3,3'); // 第二条记录有数组值，按长度2展开

// 步骤6：测试记录中混合数组和非数组值
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'string', 'rowSpan' => 1),
        array('value' => array('x', 'y', 'z', 'w'), 'rowSpan' => 2),
        array('value' => 123, 'rowSpan' => 3)
    )
))) && p('0:0,1:0,2:0,3:0') && e('1,1,1,1'); // 找到数组值长度为4，生成4行配置

// 步骤7：测试数组长度为0的边界情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array(), 'rowSpan' => 1),
        array('value' => 'test', 'rowSpan' => 2)
    )
))) && p() && e('0'); // 空数组没有元素时返回空配置