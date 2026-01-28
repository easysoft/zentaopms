#!/usr/bin/env php
<?php

/**

title=测试 screenTao::processRadarData();
timeout=0
cid=18287

- 步骤1：正常情况，第一个指标值为5第result条的0属性 @5
- 步骤2：空结果，第一个指标值为0第result条的0属性 @0
- 步骤3：单指标，数据正确第result条的0属性 @10
- 步骤4：聚合，test类别总分13第result条的0属性 @13
- 步骤5：异常配置，指标数组长度为0属性indicatorCount @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTaoTest();

// 准备测试数据
// 测试步骤1：正常雷达图数据处理
$sql1 = "SELECT 'active' as status, 5 as estimate UNION SELECT 'closed' as status, 3 as estimate UNION SELECT 'draft' as status, 2 as estimate";
$settings1 = new stdclass();
$settings1->group = array((object)array('field' => 'status'));
$settings1->metric = array(
    (object)array('key' => 'active', 'field' => 'estimate', 'name' => '活跃需求'),
    (object)array('key' => 'closed', 'field' => 'estimate', 'name' => '已关闭需求'),
    (object)array('key' => 'draft', 'field' => 'estimate', 'name' => '草稿需求')
);

// 测试步骤2：空SQL查询结果处理
$sql2 = "SELECT 'empty' as status, 0 as estimate WHERE 1=0";
$settings2 = new stdclass();
$settings2->group = array((object)array('field' => 'status'));
$settings2->metric = array(
    (object)array('key' => 'empty', 'field' => 'estimate', 'name' => '空指标')
);

// 测试步骤3：单一指标数据处理
$sql3 = "SELECT 'single' as type, 10 as value";
$settings3 = new stdclass();
$settings3->group = array((object)array('field' => 'type'));
$settings3->metric = array(
    (object)array('key' => 'single', 'field' => 'value', 'name' => '单一指标')
);

// 测试步骤4：多指标数据聚合处理
$sql4 = "SELECT 'test' as category, 5 as score UNION SELECT 'test' as category, 8 as score UNION SELECT 'prod' as category, 3 as score";
$settings4 = new stdclass();
$settings4->group = array((object)array('field' => 'category'));
$settings4->metric = array(
    (object)array('key' => 'test', 'field' => 'score', 'name' => '测试分数'),
    (object)array('key' => 'prod', 'field' => 'score', 'name' => '生产分数')
);

// 测试步骤5：异常设置对象处理（缺少必要字段）
$sql5 = "SELECT 'error' as type, 1 as count";
$settings5 = new stdclass();
$settings5->group = array((object)array('field' => 'type'));
$settings5->metric = array(); // 空的指标配置

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->processRadarDataTest($sql1, $settings1)) && p('result:0') && e('5'); // 步骤1：正常情况，第一个指标值为5
r($screenTest->processRadarDataTest($sql2, $settings2)) && p('result:0') && e('0'); // 步骤2：空结果，第一个指标值为0
r($screenTest->processRadarDataTest($sql3, $settings3)) && p('result:0') && e('10'); // 步骤3：单指标，数据正确
r($screenTest->processRadarDataTest($sql4, $settings4)) && p('result:0') && e('13'); // 步骤4：聚合，test类别总分13
r($screenTest->processRadarDataTest($sql5, $settings5)) && p('indicatorCount') && e('0'); // 步骤5：异常配置，指标数组长度为0