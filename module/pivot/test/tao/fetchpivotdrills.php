#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 准备测试数据
$pivotdrill = zenData('pivotdrill');
$pivotdrill->pivot->range('1,1,2,2,3');
$pivotdrill->version->range('1,1,2,2,3');
$pivotdrill->field->range('name,status,status,category,priority');
$pivotdrill->object->range('bug,bug,story,story,task');
$pivotdrill->whereSql->range('status = "active",deleted = "0",type = "story",,priority > 1');
$pivotdrill->condition->range('{"field":"status","operator":"=","value":"active"},{"field":"deleted","operator":"=","value":"0"},{"field":"type","operator":"=","value":"story"},{},"{"field":"priority","operator":">","value":"1"}');
$pivotdrill->status->range('published,published,published,design,published');
$pivotdrill->account->range('admin,admin,user1,user1,admin');
$pivotdrill->type->range('manual,manual,auto,auto,manual');
$pivotdrill->gen(5);

su('admin');

/**

title=测试 pivotTao::fetchPivotDrills();
timeout=0
cid=0

- 测试正常情况获取单个字段的透视表下钻配置，期望返回name字段配置第name条的field属性 @name
- 测试正常情况获取多个字段的透视表下钻配置，期望返回2个字段配置 @2
- 测试边界值不存在的透视表ID，期望返回空数组 @0
- 测试边界值不存在的版本号，期望返回空数组 @0
- 测试边界值不存在的字段名，期望返回空数组 @0

*/

$pivotTest = new pivotTest();

$result1 = $pivotTest->fetchPivotDrillsTest(1, '1', 'name');
$result2 = $pivotTest->fetchPivotDrillsTest(2, '2', array('status', 'category'));
$result3 = $pivotTest->fetchPivotDrillsTest(999, '1', 'name');
$result4 = $pivotTest->fetchPivotDrillsTest(1, 'nonexistent', 'name');
$result5 = $pivotTest->fetchPivotDrillsTest(1, '1', 'nonexistent_field');

r($result1) && p('name:field') && e('name');  // 测试正常情况获取单个字段的透视表下钻配置，期望返回name字段配置
r(count($result2)) && p() && e('2');           // 测试正常情况获取多个字段的透视表下钻配置，期望返回2个字段配置
r(count($result3)) && p() && e('0');           // 测试边界值不存在的透视表ID，期望返回空数组
r(count($result4)) && p() && e('0');           // 测试边界值不存在的版本号，期望返回空数组
r(count($result5)) && p() && e('0');           // 测试边界值不存在的字段名，期望返回空数组