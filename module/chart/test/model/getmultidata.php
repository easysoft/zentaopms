#!/usr/bin/env php
<?php

/**

title=测试 chartModel::getMultiData();
timeout=0
cid=15575

- 步骤1：正常情况 @status
- 步骤2：多指标第1条的0属性 @id
- 步骤3：带过滤器第3条的0属性 @core
- 步骤4：带排序第3条的0属性 @bug
- 步骤5：日期分组第3条的0属性 @2022

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于getMultiData方法在测试类中是模拟数据，不需要实际数据库数据

// 3. 用户登录（选择合适角色）
// su('admin'); // 模拟测试不需要登录

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常单指标数据获取
$settings1 = array(
    'xaxis' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
r($chartTest->getMultiDataTest($settings1)) && p('0') && e('status'); // 步骤1：正常情况

// 步骤2：多指标数据获取
$settings2 = array(
    'xaxis' => array(array('field' => 'priority', 'name' => '优先级', 'group' => '')),
    'yaxis' => array(
        array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'),
        array('field' => 'estimate', 'name' => '工时', 'valOrAgg' => 'sum')
    )
);
r($chartTest->getMultiDataTest($settings2)) && p('1:0') && e('id'); // 步骤2：多指标

// 步骤3：带过滤器的数据获取
$settings3 = array(
    'xaxis' => array(array('field' => 'module', 'name' => '模块', 'group' => '')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
$filters3 = array('product' => array('operator' => '=', 'value' => '1'));
r($chartTest->getMultiDataTest($settings3, '', $filters3)) && p('3:0') && e('core'); // 步骤3：带过滤器

// 步骤4：带排序的数据获取
$settings4 = array(
    'xaxis' => array(array('field' => 'type', 'name' => '类型', 'group' => '')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
r($chartTest->getMultiDataTest($settings4, '', array(), 'mysql', true)) && p('3:0') && e('bug'); // 步骤4：带排序

// 步骤5：日期分组数据获取
$settings5 = array(
    'xaxis' => array(array('field' => 'openedDate', 'name' => '创建日期', 'group' => 'YEAR')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
r($chartTest->getMultiDataTest($settings5)) && p('3:0') && e('2022'); // 步骤5：日期分组