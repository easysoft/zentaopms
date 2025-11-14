#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillDatas();
timeout=0
cid=17379

- 步骤1：正常情况测试带有value的conditions @3
- 步骤2：空conditions数组边界情况 @3
- 步骤3：使用非查询过滤模式测试 @3
- 步骤4：测试不完整的condition数据 @3
- 步骤5：带有filterValues的完整参数测试 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 数据准备（关闭BI数据库配置，避免数据库错误）
zenData('story')->gen(10);
global $config;
if(isset($config->biDB)) unset($config->biDB);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
global $tester;
$pivotModel = $tester->loadModel('pivot');

// 创建模拟的pivotState对象
class MockPivotState {
    public function setFiltersDefaultValue($filterValues) {
        return array('status' => 'active', 'type' => 'story');
    }

    public function isQueryFilter() {
        return true;
    }

    public function convertFiltersToWhere($filters) {
        return array('status' => array('operator' => '=', 'value' => '"active"'));
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
        return array('status' => array('operator' => '=', 'value' => '"active"'));
    }
}

// 创建模拟的drill对象
$drill = new stdClass();
$drill->object = 'story';
$drill->whereSql = 'WHERE t1.status = "active"';

$pivotState = new MockPivotState();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($pivotModel->getDrillDatas($pivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')), array()))) && p() && e('3'); // 步骤1：正常情况测试带有value的conditions
r(count($pivotModel->getDrillDatas($pivotState, $drill, array(), array()))) && p() && e('3'); // 步骤2：空conditions数组边界情况
$nonQueryPivotState = new MockNonQueryPivotState();
r(count($pivotModel->getDrillDatas($nonQueryPivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')), array()))) && p() && e('3'); // 步骤3：使用非查询过滤模式测试
r(count($pivotModel->getDrillDatas($pivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'drillAlias' => 't1', 'drillField' => 'status', 'queryField' => 'status')), array()))) && p() && e('3'); // 步骤4：测试不完整的condition数据
r(count($pivotModel->getDrillDatas($pivotState, $drill, array(array('field' => 'status', 'operator' => '=', 'value' => 'active', 'drillAlias' => 't1', 'drillField' => 'status')), array('status' => 'active', 'priority' => '3')))) && p() && e('3'); // 步骤5：带有filterValues的完整参数测试
