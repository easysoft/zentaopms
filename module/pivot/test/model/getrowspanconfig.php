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

// 测试步骤1：空数组边界值输入
r($pivotTest->getRowSpanConfigTest(array())) && p() && e('0');

// 测试步骤2：单记录标准rowSpan配置
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'category1', 'rowSpan' => 2),
        array('value' => 'subcategory1', 'rowSpan' => 1),
        array('value' => 'item1', 'rowSpan' => 3)
    )
))) && p('0:0,0:1,0:2') && e('2,1,3');

// 测试步骤3：数组值扩展rowSpan配置
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('tag1', 'tag2', 'tag3'), 'rowSpan' => 1),
        array('value' => 'fixed_value', 'rowSpan' => 2)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('1,2,1,2,1,2');

// 测试步骤4：多记录混合类型处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'normal', 'rowSpan' => 1),
        array('value' => 'data', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('multi1', 'multi2'), 'rowSpan' => 3),
        array('value' => 'single', 'rowSpan' => 1)
    )
))) && p('0:0,0:1,1:0,1:1,2:0,2:1') && e('1,2,3,1,3,1');

// 测试步骤5：异常值和边界条件处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => null, 'rowSpan' => 1),
        array('value' => 'test')
    )
))) && p('0:0,0:1') && e('1,~~');