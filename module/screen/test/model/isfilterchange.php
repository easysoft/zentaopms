#!/usr/bin/env php
<?php

/**

title=测试 screenModel::isFilterChange();
timeout=0
cid=18266

- 步骤1：最新过滤器为空 @1
- 步骤2：过滤器数量不同 @1
- 步骤3：相同的过滤器数组 @0
- 步骤4：过滤器field字段不同 @1
- 步骤5：查询筛选器from字段不同 @1
- 步骤6：select类型的typeOption字段不同 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$screenTest = new screenTest();

// 4. 准备测试数据
// 构造标准过滤器对象
$filter1 = new stdClass();
$filter1->field = 'status';
$filter1->name = '状态';
$filter1->type = 'select';

$filter2 = new stdClass();
$filter2->field = 'priority';
$filter2->name = '优先级';
$filter2->type = 'input';

$filter3 = new stdClass();
$filter3->field = 'assignedTo';
$filter3->name = '指派给';
$filter3->type = 'select';
$filter3->from = 'query';
$filter3->typeOption = 'option1';

$filter4 = new stdClass();
$filter4->field = 'assignedTo';
$filter4->name = '指派给';
$filter4->type = 'select';
$filter4->from = 'query';
$filter4->typeOption = 'option2';

$filter5 = new stdClass();
$filter5->field = 'assignedTo';
$filter5->name = '指派给';
$filter5->type = 'select';

$oldFilters = array($filter1, $filter2);
$latestFilters = array($filter1, $filter2);
$differentCountFilters = array($filter1);
$differentFieldFilters = array($filter3, $filter2);
$queryFilterOld = array($filter3);
$queryFilterNew = array($filter5);
$differentTypeOptionFilters = array($filter4);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->isFilterChangeTest($oldFilters, array())) && p() && e('1'); // 步骤1：最新过滤器为空
r($screenTest->isFilterChangeTest($oldFilters, $differentCountFilters)) && p() && e('1'); // 步骤2：过滤器数量不同
r($screenTest->isFilterChangeTest($oldFilters, $latestFilters)) && p() && e('0'); // 步骤3：相同的过滤器数组
r($screenTest->isFilterChangeTest($oldFilters, $differentFieldFilters)) && p() && e('1'); // 步骤4：过滤器field字段不同
r($screenTest->isFilterChangeTest($queryFilterOld, $queryFilterNew)) && p() && e('1'); // 步骤5：查询筛选器from字段不同
r($screenTest->isFilterChangeTest($queryFilterOld, $differentTypeOptionFilters)) && p() && e('1'); // 步骤6：select类型的typeOption字段不同