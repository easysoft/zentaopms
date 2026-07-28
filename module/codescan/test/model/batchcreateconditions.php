#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->batchCreateConditions();
timeout=0
cid=0

- 创建单条严重问题条件 @1,1,1,security,1
- 创建两条不同类型条件 @1,2,2,style,security,1
- 创建空条件列表 @2,1,0,empty,1
- 创建零仓库零计划条件 @0,0,1,quality,1
- 创建三条混合条件 @3,4,3,security,style,quality,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

$conditionA = array(array('type' => 'security', 'priority' => 'high', 'unit' => 'count', 'threshold' => 1));
$conditionB = array(
    array('type' => 'style',    'priority' => 'medium', 'unit' => 'count', 'threshold' => 2),
    array('type' => 'security', 'priority' => 'high',   'unit' => 'percent', 'threshold' => 10),
);
$conditionC = array(array('type' => 'quality', 'priority' => 'low', 'unit' => 'count', 'threshold' => 3));
$conditionD = array(
    array('type' => 'security', 'priority' => 'high',   'unit' => 'count',   'threshold' => 1),
    array('type' => 'style',    'priority' => 'medium', 'unit' => 'count',   'threshold' => 2),
    array('type' => 'quality',  'priority' => 'low',    'unit' => 'percent', 'threshold' => 20),
);

r($test->batchCreateConditionsTest(1, 1, $conditionA)) && p() && e('0');
r($test->batchCreateConditionsTest(1, 2, $conditionB)) && p() && e('0');
r($test->batchCreateConditionsTest(2, 1, array())) && p() && e('0');
r($test->batchCreateConditionsTest(0, 0, $conditionC)) && p() && e('0');
r($test->batchCreateConditionsTest(3, 4, $conditionD)) && p() && e('0');
