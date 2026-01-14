#!/usr/bin/env php
<?php

/**

title=测试 screenModel::initMetricComponent();
timeout=0
cid=18264

- 步骤1：正常情况 - 验证id第0条的id属性 @1
- 步骤2：已有component - 保持原标题第0条的title属性 @自定义标题
- 步骤3：验证sourceID设置第0条的sourceID属性 @3
- 步骤4：保持已有id第0条的id属性 @100
- 步骤5：验证type设置第0条的type属性 @metric

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 测试数据准备
$metric1 = (object)array('id' => 1, 'name' => '测试指标1');
$metric2 = (object)array('id' => 2, 'name' => '测试指标2');
$metric3 = (object)array('id' => 3, 'name' => '测试指标3');
$metric4 = (object)array('id' => 4, 'name' => '测试指标4');
$metric5 = (object)array('id' => 5, 'name' => '测试指标5');

$component1 = null;
$component2 = (object)array('title' => '自定义标题');
$component3 = null;
$component4 = (object)array('id' => 100);
$component5 = null;

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->initMetricComponentTest($metric1, $component1)) && p('0:id') && e('1'); // 步骤1：正常情况 - 验证id
r($screenTest->initMetricComponentTest($metric2, $component2)) && p('0:title') && e('自定义标题'); // 步骤2：已有component - 保持原标题
r($screenTest->initMetricComponentTest($metric3, $component3)) && p('0:sourceID') && e('3'); // 步骤3：验证sourceID设置
r($screenTest->initMetricComponentTest($metric4, $component4)) && p('0:id') && e('100'); // 步骤4：保持已有id
r($screenTest->initMetricComponentTest($metric5, $component5)) && p('0:type') && e('metric'); // 步骤5：验证type设置