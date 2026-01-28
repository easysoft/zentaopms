#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getCellData();
timeout=0
cid=17374

- 步骤1：sum统计属性value @35
- 步骤2：count统计属性value @3
- 步骤3：max统计属性value @20
- 步骤4：min统计属性value @5
- 步骤5：showOrigin=1返回原始数据属性isGroup @~~
- 步骤6：空数组处理属性value @0
- 步骤7：单条记录求平均值属性value @15

*/

// 1. 导入依赖（路径固定，不可修改）
// 尝试加载完整环境，如果失败则使用简化模式
$useFullEnvironment = true;
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
} catch (Exception $e) {
    $useFullEnvironment = false;
    // 简化模式：检查是否已经定义了必要的常量和类
    if(!defined('RUN_MODE')) define('RUN_MODE', 'test');

    if(!class_exists('baseRouter', false)) {
        include dirname(__FILE__, 5) . '/framework/router.class.php';
    }
    if(!class_exists('model', false)) {
        include dirname(__FILE__, 5) . '/framework/model.class.php';
    }
    if(!class_exists('baseHelper', false)) {
        include dirname(__FILE__, 5) . '/framework/helper.class.php';
    }

    // 创建基本配置对象
    if(!isset($config)) {
        $config = new stdClass();
        $config->db = new stdClass();
    }
}

include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
// 如果完整环境可用，使用标准用户切换
if($useFullEnvironment && function_exists('su')) {
    try {
        su('admin');
    } catch (Exception $e) {
        // 登录失败，继续测试
    }
}

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 准备测试数据
$records = array(
    array('id' => 1, 'score' => 10, 'status' => 'active'),
    array('id' => 2, 'score' => 20, 'status' => 'active'),
    array('id' => 3, 'score' => 5, 'status' => 'inactive')
);

$emptyRecords = array();
$singleRecord = array(
    array('id' => 1, 'score' => 15, 'status' => 'active')
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getCellDataTest('col1', $records, array('field' => 'score', 'stat' => 'sum'))) && p('value') && e('35'); // 步骤1：sum统计
r($pivotTest->getCellDataTest('col2', $records, array('field' => 'score', 'stat' => 'count'))) && p('value') && e('3'); // 步骤2：count统计
r($pivotTest->getCellDataTest('col3', $records, array('field' => 'score', 'stat' => 'max'))) && p('value') && e('20'); // 步骤3：max统计
r($pivotTest->getCellDataTest('col4', $records, array('field' => 'score', 'stat' => 'min'))) && p('value') && e('5'); // 步骤4：min统计
r($pivotTest->getCellDataTest('col5', $records, array('field' => 'score', 'showOrigin' => 1))) && p('isGroup') && e('~~'); // 步骤5：showOrigin=1返回原始数据
r($pivotTest->getCellDataTest('col6', $emptyRecords, array('field' => 'score', 'stat' => 'count'))) && p('value') && e('0'); // 步骤6：空数组处理
r($pivotTest->getCellDataTest('col7', $singleRecord, array('field' => 'score', 'stat' => 'avg'))) && p('value') && e('15'); // 步骤7：单条记录求平均值