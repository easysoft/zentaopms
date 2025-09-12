#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::fetchPivotDrills();
timeout=0
cid=0

- 步骤1：正常情况 - 获取单个字段第name条的field属性 @name
- 步骤2：正常情况 - 获取多个字段 @2
- 步骤3：边界值 - 不存在的透视表ID @0
- 步骤4：边界值 - 不存在的版本号 @0
- 步骤5：边界值 - 不存在的字段名 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 直接插入测试数据，避免zendata问题
global $tester;

// 清理旧数据
$tester->dao->delete()->from(TABLE_PIVOTDRILL)->exec();

// 插入测试数据
$testData = array(
    array('pivot' => 1, 'version' => '1', 'field' => 'name', 'object' => 'bug', 'whereSql' => 'status = "active"', 'condition' => '{"field":"status","operator":"=","value":"active"}', 'status' => 'published', 'account' => 'admin', 'type' => 'manual'),
    array('pivot' => 1, 'version' => '1', 'field' => 'status', 'object' => 'bug', 'whereSql' => 'deleted = "0"', 'condition' => '{"field":"deleted","operator":"=","value":"0"}', 'status' => 'published', 'account' => 'admin', 'type' => 'manual'),
    array('pivot' => 2, 'version' => '2', 'field' => 'status', 'object' => 'story', 'whereSql' => 'type = "story"', 'condition' => '{"field":"type","operator":"=","value":"story"}', 'status' => 'published', 'account' => 'user1', 'type' => 'auto'),
    array('pivot' => 2, 'version' => '2', 'field' => 'category', 'object' => 'story', 'whereSql' => '', 'condition' => '{}', 'status' => 'design', 'account' => 'user1', 'type' => 'auto'),
    array('pivot' => 3, 'version' => '3', 'field' => 'priority', 'object' => 'task', 'whereSql' => 'priority > 1', 'condition' => '{"field":"priority","operator":">","value":"1"}', 'status' => 'published', 'account' => 'admin', 'type' => 'manual')
);

foreach($testData as $data) {
    $tester->dao->insert(TABLE_PIVOTDRILL)->data($data)->exec();
}

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 3. 用户登录（选择合适角色）
su('admin');

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->fetchPivotDrillsTest(1, '1', 'name')) && p('name:field') && e('name'); // 步骤1：正常情况 - 获取单个字段
r(count($pivotTest->fetchPivotDrillsTest(2, '2', array('status', 'category')))) && p() && e('2'); // 步骤2：正常情况 - 获取多个字段
r(count($pivotTest->fetchPivotDrillsTest(999, '1', 'name'))) && p() && e('0'); // 步骤3：边界值 - 不存在的透视表ID
r(count($pivotTest->fetchPivotDrillsTest(1, 'nonexistent', 'name'))) && p() && e('0'); // 步骤4：边界值 - 不存在的版本号
r(count($pivotTest->fetchPivotDrillsTest(1, '1', 'nonexistent_field'))) && p() && e('0'); // 步骤5：边界值 - 不存在的字段名