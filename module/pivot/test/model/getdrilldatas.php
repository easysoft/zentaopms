#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillDatas();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：空conditions数组边界情况 @1
- 步骤3：使用非查询过滤模式 @1
- 步骤4：空whereSql的drill对象 @1
- 步骤5：带有filterValues的完整参数 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 创建模拟的pivotState对象
class MockPivotState {
    public function setFiltersDefaultValue($filterValues) {
        return array('status' => 'active', 'type' => 'story');
    }

    public function isQueryFilter() {
        return true;
    }

    public function convertFiltersToWhere($filters) {
        return array('WHERE status = "active"');
    }
}

class MockNonQueryPivotState {
    public function setFiltersDefaultValue($filterValues) {
        return array('status' => 'active');
    }

    public function isQueryFilter() {
        return false;
    }

    public function convertFiltersToWhere($filters) {
        return array('WHERE status = "active"');
    }
}

// 创建模拟的drill对象
$drill = new stdClass();
$drill->object = 'story';
$drill->whereSql = 'WHERE t1.status = "active"';

$pivotState = new MockPivotState();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $pivotTest->getDrillDatasTest($pivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')), array());
r(is_array($result1)) && p() && e('1'); // 步骤1：正常情况

$result2 = $pivotTest->getDrillDatasTest($pivotState, $drill, array(), array());
r(is_array($result2)) && p() && e('1'); // 步骤2：空conditions数组边界情况

$nonQueryPivotState = new MockNonQueryPivotState();
$result3 = $pivotTest->getDrillDatasTest($nonQueryPivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')));
r(is_array($result3)) && p() && e('1'); // 步骤3：使用非查询过滤模式

$incompleteDrill = new stdClass();
$incompleteDrill->object = 'story';
$incompleteDrill->whereSql = '';
$result4 = $pivotTest->getDrillDatasTest($pivotState, $incompleteDrill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')));
r(is_array($result4)) && p() && e('1'); // 步骤4：空whereSql的drill对象

$result5 = $pivotTest->getDrillDatasTest($pivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'drillAlias' => 't1', 'drillField' => 'status')), array('status' => 'active', 'priority' => '3'));
r(is_array($result5)) && p() && e('1'); // 步骤5：带有filterValues的完整参数