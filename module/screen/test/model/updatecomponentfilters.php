#!/usr/bin/env php
<?php

/**

title=测试 screenModel::updateComponentFilters();
timeout=0
cid=18285

- 执行$result1->chartConfig->filters) && count($result1->chartConfig->filters) == 1 && $result1->chartConfig->filters[0]->field == 'name @1
- 执行$result2->chartConfig->filters[0]->default == 'closed @1
- 执行$result3->chartConfig->filters[0]->field == 'newField' && is_array($result3->chartConfig->noSetupGlobalFilterList) @1
- 执行chartConfig模块的filters) == 2方法  @1
- 执行$result5->chartConfig->filters) && count($result5->chartConfig->filters) == 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

// 测试步骤1：组件没有chartConfig.filters时设置latestFilters
$component1 = new stdclass();
$component1->chartConfig = new stdclass();
$latestFilters1 = array(
    (object)array('field' => 'name', 'name' => '姓名', 'type' => 'input', 'default' => 'test')
);
$result1 = $screenTest->updateComponentFiltersTest($component1, $latestFilters1);
r(isset($result1->chartConfig->filters) && count($result1->chartConfig->filters) == 1 && $result1->chartConfig->filters[0]->field == 'name') && p() && e(1);

// 测试步骤2：组件filters未发生变化且无POST filters时检查默认值更新
$component2 = new stdclass();
$component2->chartConfig = new stdclass();
$component2->chartConfig->filters = array(
    (object)array('field' => 'status', 'name' => '状态', 'type' => 'select', 'default' => 'open')
);
$latestFilters2 = array(
    (object)array('field' => 'status', 'name' => '状态', 'type' => 'select', 'default' => 'closed')
);
$result2 = $screenTest->updateComponentFiltersTest($component2, $latestFilters2);
r($result2->chartConfig->filters[0]->default == 'closed') && p() && e(1);

// 测试步骤3：组件filters发生变化时重置filters
$component3 = new stdclass();
$component3->chartConfig = new stdclass();
$component3->chartConfig->filters = array(
    (object)array('field' => 'oldField', 'name' => '旧字段', 'type' => 'input')
);
$latestFilters3 = array(
    (object)array('field' => 'newField', 'name' => '新字段', 'type' => 'input')
);
$result3 = $screenTest->updateComponentFiltersTest($component3, $latestFilters3);
r($result3->chartConfig->filters[0]->field == 'newField' && is_array($result3->chartConfig->noSetupGlobalFilterList)) && p() && e(1);

// 测试步骤4：filters数量不同时判断为变化
$component4 = new stdclass();
$component4->chartConfig = new stdclass();
$component4->chartConfig->filters = array(
    (object)array('field' => 'field1', 'name' => '字段1', 'type' => 'input')
);
$latestFilters4 = array(
    (object)array('field' => 'field1', 'name' => '字段1', 'type' => 'input'),
    (object)array('field' => 'field2', 'name' => '字段2', 'type' => 'select')
);
$result4 = $screenTest->updateComponentFiltersTest($component4, $latestFilters4);
r(count($result4->chartConfig->filters) == 2) && p() && e(1);

// 测试步骤5：测试空latestFilters的处理
$component5 = new stdclass();
$component5->chartConfig = new stdclass();
$component5->chartConfig->filters = array(
    (object)array('field' => 'priority', 'name' => '优先级', 'type' => 'select')
);
$latestFilters5 = array();
$result5 = $screenTest->updateComponentFiltersTest($component5, $latestFilters5);
r(is_array($result5->chartConfig->filters) && count($result5->chartConfig->filters) == 0) && p() && e(1);