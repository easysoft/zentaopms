#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowSpanConfig();
timeout=0
cid=0

- 执行pivotTest模块的getRowSpanConfigTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：空数组边界值处理
r($pivotTest->getRowSpanConfigTest(array())) && p() && e('0');

// 测试步骤2：单记录普通值处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'test1', 'rowSpan' => 2),
        array('value' => 'test2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1') && e('2,1');

// 测试步骤3：单记录数组值处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('item1', 'item2', 'item3'), 'rowSpan' => 2),
        array('value' => 'normal', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('2,1,2,1,2,1');

// 测试步骤4：多记录普通值处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'row1col1', 'rowSpan' => 1),
        array('value' => 'row1col2', 'rowSpan' => 2)
    ),
    array(
        array('value' => 'row2col1', 'rowSpan' => 3),
        array('value' => 'row2col2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1') && e('1,2,3,1');

// 测试步骤5：混合数组和普通值处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'normal1', 'rowSpan' => 1),
        array('value' => 'normal2', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('a', 'b'), 'rowSpan' => 3),
        array('value' => 'normal3', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('1,2,3,1,3,1');

// 测试步骤6：空数组值边界处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array(), 'rowSpan' => 1),
        array('value' => 'test', 'rowSpan' => 2)
    )
))) && p('0:0,0:1') && e('1,2');

// 测试步骤7：复杂嵌套数组值处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'string', 'rowSpan' => 1),
        array('value' => array('x', 'y', 'z', 'w'), 'rowSpan' => 2),
        array('value' => 123, 'rowSpan' => 3)
    )
))) && p('0:0,0:1,0:2,1:0,1:1,1:2,2:0,2:1,2:2,3:0,3:1,3:2') && e('1,2,3,1,2,3,1,2,3,1,2,3');

// 测试步骤8：多重数组值记录处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('first', 'second'), 'rowSpan' => 1),
        array('value' => 'normal1', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('third', 'fourth', 'fifth'), 'rowSpan' => 3),
        array('value' => 'normal2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1,3:0,3:1,4:0,4:1') && e('1,2,1,2,3,1,3,1,3,1');