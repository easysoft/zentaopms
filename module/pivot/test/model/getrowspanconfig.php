#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowSpanConfig();
timeout=0
cid=17399

- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData1  @0
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData2  @1
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData3  @3
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData4  @3
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData5  @1
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData6  @2
- 执行pivotTest模块的getRowSpanConfigTest方法，参数是$testData7  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 测试步骤1：空数组边界值输入
$testData1 = array();
r(count($pivotTest->getRowSpanConfigTest($testData1))) && p() && e('0');

// 测试步骤2：单记录正常rowSpan配置
$testData2 = array(
    array(
        array('value' => 'category1', 'rowSpan' => 2),
        array('value' => 'subcategory1', 'rowSpan' => 1),
        array('value' => 'item1', 'rowSpan' => 3)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData2))) && p() && e('1');

// 测试步骤3：数组值扩展rowSpan配置
$testData3 = array(
    array(
        array('value' => array('tag1', 'tag2', 'tag3'), 'rowSpan' => 1),
        array('value' => 'fixed_value', 'rowSpan' => 2)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData3))) && p() && e('3');

// 测试步骤4：多记录混合类型处理
$testData4 = array(
    array(
        array('value' => 'normal', 'rowSpan' => 1),
        array('value' => 'data', 'rowSpan' => 2)
    ),
    array(
        array('value' => array('multi1', 'multi2'), 'rowSpan' => 3),
        array('value' => 'single', 'rowSpan' => 1)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData4))) && p() && e('3');

// 测试步骤5：缺失rowSpan属性的异常情况
$testData5 = array(
    array(
        array('value' => 'normal_value'),
        array('value' => 'test_value', 'rowSpan' => 2)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData5))) && p() && e('1');

// 测试步骤6：深度嵌套数组值测试
$testData6 = array(
    array(
        array('value' => array('item1', 'item2', 'item3', 'item4'), 'rowSpan' => 2),
        array('value' => array('subitem1', 'subitem2'), 'rowSpan' => 1)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData6))) && p() && e('2');

// 测试步骤7：特殊数据类型边界测试
$testData7 = array(
    array(
        array('value' => null, 'rowSpan' => 1),
        array('value' => 0, 'rowSpan' => 2),
        array('value' => false, 'rowSpan' => 3)
    )
);
r(count($pivotTest->getRowSpanConfigTest($testData7))) && p() && e('1');