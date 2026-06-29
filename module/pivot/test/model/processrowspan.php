#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=17427

- 步骤1：基础分组合并测试 @2
- 步骤2：基础分组合并后的尾行测试 @1
- 步骤3：多级分组中次级分组不合并 @1
- 步骤4：数组值会影响行合并跨度 @3
- 步骤5：总计标记不会继续向下合并 @1
- 步骤6：空数组边界测试 @0
- 步骤7：复杂三级分组测试 @3
- 步骤8：复杂三级分组中的次级分组测试 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

$basicData = array(
    array('group1' => array('value' => 'A'), 'col1' => array('value' => 100), 'col2' => array('value' => 200)),
    array('group1' => array('value' => 'A'), 'col1' => array('value' => 150), 'col2' => array('value' => 250)),
    array('group1' => array('value' => 'B'), 'col1' => array('value' => 300), 'col2' => array('value' => 350))
);
$basicResult = $pivotTest->processRowSpanTest($basicData, array('group1'));

$multiData = array(
    array('group1' => array('value' => 'A'), 'group2' => array('value' => 'X'), 'col1' => array('value' => 100)),
    array('group1' => array('value' => 'A'), 'group2' => array('value' => 'Y'), 'col1' => array('value' => 150)),
    array('group1' => array('value' => 'B'), 'group2' => array('value' => 'X'), 'col1' => array('value' => 200))
);
$multiResult = $pivotTest->processRowSpanTest($multiData, array('group1', 'group2'));

$arrayData = array(
    array('group1' => array('value' => 'A'), 'col1' => array('value' => array(100, 150, 200)), 'col2' => array('value' => 300)),
    array('group1' => array('value' => 'B'), 'col1' => array('value' => 400), 'col2' => array('value' => array(500, 600)))
);
$arrayResult = $pivotTest->processRowSpanTest($arrayData, array('group1'));

$totalData = array(
    array('group1' => array('value' => '$total$'), 'col1' => array('value' => 100)),
    array('group1' => array('value' => '$total$'), 'col1' => array('value' => 150)),
    array('group1' => array('value' => 'Normal'), 'col1' => array('value' => 200)),
    array('group1' => array('value' => 'Normal'), 'col1' => array('value' => 250))
);
$totalResult = $pivotTest->processRowSpanTest($totalData, array('group1'));

$emptyResult = $pivotTest->processRowSpanTest(array(), array('group1'));

$complexData = array(
    array('category' => array('value' => 'A'), 'type' => array('value' => 'T1'), 'subtype' => array('value' => 'S1'), 'data' => array('value' => 10)),
    array('category' => array('value' => 'A'), 'type' => array('value' => 'T1'), 'subtype' => array('value' => 'S2'), 'data' => array('value' => 20)),
    array('category' => array('value' => 'A'), 'type' => array('value' => 'T2'), 'subtype' => array('value' => 'S1'), 'data' => array('value' => 30)),
    array('category' => array('value' => 'B'), 'type' => array('value' => 'T1'), 'subtype' => array('value' => 'S1'), 'data' => array('value' => 40))
);
$complexResult = $pivotTest->processRowSpanTest($complexData, array('category', 'type', 'subtype'));

r($basicResult[0]['group1']['rowSpan'])    && p() && e('2');
r($basicResult[2]['group1']['rowSpan'])    && p() && e('1');
r($multiResult[0]['group2']['rowSpan'])    && p() && e('1');
r($arrayResult[0]['group1']['rowSpan'])    && p() && e('3');
r($totalResult[0]['group1']['rowSpan'])    && p() && e('1');
r($emptyResult)                            && p() && e('0');
r($complexResult[0]['category']['rowSpan'])&& p() && e('3');
r($complexResult[0]['type']['rowSpan'])    && p() && e('2');
