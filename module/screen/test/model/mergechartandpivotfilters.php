#!/usr/bin/env php
<?php

/**

title=测试 screenModel::mergeChartAndPivotFilters();
timeout=0
cid=18267

- 执行screenTest模块的mergeChartAndPivotFiltersTest方法，参数是'chart', $chartObject, 1, $filters 第0条的filters属性 @[]
- 执行screenTest模块的mergeChartAndPivotFiltersTest方法，参数是'pivot', $pivotObject, 1, $pivotFilters 第0条的sql属性 @SELECT * FROM pivot_test
- 执行screenTest模块的mergeChartAndPivotFiltersTest方法，参数是'chart', $testObject, 1, array  @1
- 执行screenTest模块的mergeChartAndPivotFiltersTest方法，参数是'chart', $testObject2, 1, array  @1
- 执行screenTest模块的mergeChartAndPivotFiltersTest方法，参数是'pivot', $testObject3, 1, array  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 4. 测试步骤
// 步骤1：测试chart类型的正常情况，空过滤器
$chartObject = new stdClass();
$chartObject->filters = '[]';
$filters = array();
r($screenTest->mergeChartAndPivotFiltersTest('chart', $chartObject, 1, $filters)) && p('0:filters') && e('[]');

// 步骤2：测试pivot类型的正常情况，空过滤器
$pivotObject = new stdClass();
$pivotObject->filters = '[]';
$pivotObject->sql = 'SELECT * FROM pivot_test';
$pivotFilters = array();
r($screenTest->mergeChartAndPivotFiltersTest('pivot', $pivotObject, 1, $pivotFilters)) && p('0:sql') && e('SELECT * FROM pivot_test');

// 步骤3：测试方法正常执行，不返回错误
$testObject = new stdClass();
$testObject->filters = '[]';
r($screenTest->mergeChartAndPivotFiltersTest('chart', $testObject, 1, array()) !== false) && p() && e(1);

// 步骤4：测试方法正常执行，不返回错误
$testObject2 = new stdClass();
$testObject2->filters = '[]';
r($screenTest->mergeChartAndPivotFiltersTest('chart', $testObject2, 1, array()) !== false) && p() && e(1);

// 步骤5：测试方法正常执行，不返回错误
$testObject3 = new stdClass();
$testObject3->filters = '[]';
$testObject3->sql = 'SELECT * FROM test';
r($screenTest->mergeChartAndPivotFiltersTest('pivot', $testObject3, 1, array()) !== false) && p() && e(1);