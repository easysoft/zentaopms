#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 bugZen::getExportFields();
timeout=0
cid=15453

- 执行$containsBasicFields @1
- 执行$branchRemoved @1
- 执行$planRemoved @1
- 执行$hasFields @1
- 执行$validResult @1

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'export';

$zen = initReference('bug');
$func = $zen->getMethod('getExportFields');

// 测试步骤1：product为false的情况，应该包含基础字段
$result = $func->invokeArgs($zen->newInstance(), [0, false]);
$containsBasicFields = strpos($result, 'id') !== false && strpos($result, 'title') !== false && strpos($result, 'status') !== false;
r($containsBasicFields) && p() && e('1');

// 测试步骤2：normal产品类型，应该移除branch字段
$normalProduct = (object)array('type' => 'normal', 'shadow' => '0');
$result = $func->invokeArgs($zen->newInstance(), [0, $normalProduct]);
$branchRemoved = strpos($result, ',branch,') === false && strpos($result, 'id') !== false;
r($branchRemoved) && p() && e('1');

// 测试步骤3：shadow产品，应该移除plan字段
$shadowProduct = (object)array('type' => 'branch', 'shadow' => '1');
$result = $func->invokeArgs($zen->newInstance(), [0, $shadowProduct]);
$planRemoved = strpos($result, ',plan,') === false && strpos($result, 'id') !== false;
r($planRemoved) && p() && e('1');

// 测试步骤4：设置项目环境，验证执行相关字段处理
$tester->app->tab = 'project';
$result = $func->invokeArgs($zen->newInstance(), [101, $normalProduct]);
$hasFields = strpos($result, 'id') !== false && strpos($result, 'title') !== false;
r($hasFields) && p() && e('1');

// 测试步骤5：边界情况，executionID为0，product为空对象
$emptyProduct = (object)array('shadow' => '0');
$result = $func->invokeArgs($zen->newInstance(), [0, $emptyProduct]);
$validResult = is_string($result) && strlen($result) > 0;
r($validResult) && p() && e('1');