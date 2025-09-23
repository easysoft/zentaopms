#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowSpanConfig();
timeout=0
cid=0

- 空数组返回空配置 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 步骤1：测试空记录数组处理
r($pivotTest->getRowSpanConfigTest(array())) && p() && e('0'); // 空数组返回空配置

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
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('2,1,2,1,2,1'); // 根据数组长度3生成3行配置

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
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('1,2,3,1,3,1'); // 第一条记录1行，第二条记录按数组长度2展开

// 步骤6：测试记录中混合数组和非数组值
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'string', 'rowSpan' => 1),
        array('value' => array('x', 'y', 'z', 'w'), 'rowSpan' => 2),
        array('value' => 123, 'rowSpan' => 3)
    )
))) && p('0:0,0:1,0:2,1:0,1:1,1:2,2:0,2:1,2:2,3:0,3:1,3:2') && e('1,2,3,1,2,3,1,2,3,1,2,3'); // 找到数组值长度为4，生成4行配置

// 步骤7：测试空数组值的边界情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array(), 'rowSpan' => 1),
        array('value' => 'test', 'rowSpan' => 2)
    )
))) && p('0:0,0:1') && e('1,2'); // 空数组时使用默认值1，生成1行配置

// 步骤8：测试多个记录都包含数组值
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('first', 'second'), 'rowSpan' => 1),
        array('value' => 'normal1', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('third', 'fourth', 'fifth'), 'rowSpan' => 3),
        array('value' => 'normal2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1,3:0,3:1,4:0,4:1') && e('1,2,1,2,3,1,3,1,3,1'); // 第一条记录2行，第二条记录3行

// 步骤9：测试数组值包含null和空字符串
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array(null, '', 'valid'), 'rowSpan' => 2),
        array('value' => 'test', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('2,1,2,1,2,1'); // 数组包含特殊值，长度为3

// 步骤10：测试大数组值的性能表现
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => range(1, 100), 'rowSpan' => 1),
        array('value' => 'large_array_test', 'rowSpan' => 2)
    )
))) && p() && e('100'); // 大数组长度100，验证处理正确性