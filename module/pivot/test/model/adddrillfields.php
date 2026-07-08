#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addDrillFields();
timeout=0
cid=0

- 步骤1：叶子节点补充下钻字段 @1
- 步骤2：叶子节点合并已有下钻字段 @2
- 步骤3：嵌套节点对子节点补充下钻字段 @status
- 步骤4：total 节点不补充下钻字段 @0
- 步骤5：多层嵌套节点保留下钻字段数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest   = new pivotModelTest();
$drillFields = array(array('field' => 'status', 'value' => 'active'));

$leafCell     = array('value' => 10, 'isGroup' => false);
$existingCell = array('value' => 8, 'drillFields' => array(array('field' => 'project', 'value' => 1)));
$nestedCell   = array('sliceA' => array('value' => 2), 'sliceB' => array('value' => 3), 'total' => array('value' => 5));
$deepCell     = array('team1' => array('module1' => array('value' => 1), 'module2' => array('value' => 2)), 'total' => array('value' => 3));

$leafResult     = $pivotTest->addDrillFieldsTest($leafCell, $drillFields);
$existingResult = $pivotTest->addDrillFieldsTest($existingCell, $drillFields);
$nestedResult   = $pivotTest->addDrillFieldsTest($nestedCell, $drillFields);
$deepResult     = $pivotTest->addDrillFieldsTest($deepCell, array(array('field' => 'project', 'value' => 2), array('field' => 'execution', 'value' => 3)));

r(count($leafResult['drillFields'])) && p() && e('1');
r(count($existingResult['drillFields'])) && p() && e('2');
r($nestedResult['sliceA']['drillFields'][0]['field']) && p() && e('status');
r(isset($nestedResult['total']['drillFields'])) && p() && e('0');
r(count($deepResult['team1']['module1']['drillFields'])) && p() && e('2');
