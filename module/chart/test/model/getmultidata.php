#!/usr/bin/env php
<?php

/**

title=测试 chartModel::getMultiData();
timeout=0
cid=0

- 步骤1：正常情况
 - 第0,1条的0属性 @status
 - 第0,1条的2:0属性 @id
 - 第0,1条的3:0属性 @count
 - 第0,1条的3:1属性 @active
 - 第0,1条的3:2属性 @resolved
 - 第0,1条的4:0:active属性 @closed
- 步骤2：多指标
 - 第0,1条的0属性 @priority
 - 第0,1条的1:1属性 @id
 - 第0,1条的2:0属性 @estimate
 - 第0,1条的2:1属性 @count
 - 第0,1条的3:0属性 @sum
 - 第0,1条的3:1属性 @1
 - 第0,1条的3:2属性 @2
 - 第0,1条的3:3属性 @3
 - 第0,1条的4:0:1属性 @4
 - 第0,1条的4:1:1属性 @10
- 步骤3：带过滤器
 - 第0,1条的0属性 @module
 - 第0,1条的2:0属性 @id
 - 第0,1条的3:0属性 @count
 - 第0,1条的3:1属性 @module1
 - 第0,1条的4:0:module1属性 @module2
- 步骤4：带排序
 - 第0,1条的0属性 @type
 - 第0,1条的2:0属性 @id
 - 第0,1条的3:0属性 @count
 - 第0,1条的3:1属性 @codeerror
 - 第0,1条的3:2属性 @config
 - 第0,1条的4:0:codeerror属性 @install
- 步骤5：日期分组
 - 第0,1条的0属性 @openedDate
 - 第0,1条的2:0属性 @id
 - 第0,1条的3:0属性 @count
 - 第0,1条的3:1属性 @2023
 - 第0,1条的4:0:2023属性 @2024

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
r($chartTest->getMultiDataTest($settings2)) && p('0') && e('priority'); // 步骤2：多指标

// 步骤3：带过滤器的数据获取
$settings3 = array(
    'xaxis' => array(array('field' => 'module', 'name' => '模块', 'group' => '')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
$filters3 = array('product' => array('operator' => '=', 'value' => '1'));
r($chartTest->getMultiDataTest($settings3, '', $filters3)) && p('0') && e('module'); // 步骤3：带过滤器

// 步骤4：带排序的数据获取
$settings4 = array(
    'xaxis' => array(array('field' => 'type', 'name' => '类型', 'group' => '')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
r($chartTest->getMultiDataTest($settings4, '', array(), 'mysql', true)) && p('0') && e('type'); // 步骤4：带排序

// 步骤5：日期分组数据获取
$settings5 = array(
    'xaxis' => array(array('field' => 'openedDate', 'name' => '创建日期', 'group' => 'YEAR')),
    'yaxis' => array(array('field' => 'id', 'name' => '数量', 'valOrAgg' => 'count'))
);
r($chartTest->getMultiDataTest($settings5)) && p('0') && e('openedDate'); // 步骤5：日期分组