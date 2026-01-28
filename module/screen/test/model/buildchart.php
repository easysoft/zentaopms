#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildChart();
timeout=0
cid=18206

- 步骤1：card图表第option条的dataset属性 @200
- 步骤2：另一个card图表第option条的dataset属性 @200
- 步骤3：第三个card图表第option条的dataset属性 @200
- 步骤4：第四个card图表第option条的dataset属性 @200
- 步骤5：无效ID返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 用户登录
su('admin');

// 创建测试实例
$screenTest = new screenModelTest();

// 测试步骤1：测试card类型图表构建
$cardComponent = new stdclass();
$cardComponent->sourceID = 1001;
r($screenTest->buildChartTest($cardComponent)) && p('option:dataset') && e('200'); // 步骤1：card图表

// 测试步骤2：测试另一个card类型图表构建
$cardComponent2 = new stdclass();
$cardComponent2->sourceID = 1001;
r($screenTest->buildChartTest($cardComponent2)) && p('option:dataset') && e('200'); // 步骤2：另一个card图表

// 测试步骤3：测试第三个card类型图表构建
$cardComponent3 = new stdclass();
$cardComponent3->sourceID = 1001;
r($screenTest->buildChartTest($cardComponent3)) && p('option:dataset') && e('200'); // 步骤3：第三个card图表

// 测试步骤4：测试第四个card类型图表构建
$cardComponent4 = new stdclass();
$cardComponent4->sourceID = 1001;
r($screenTest->buildChartTest($cardComponent4)) && p('option:dataset') && e('200'); // 步骤4：第四个card图表

// 测试步骤5：测试无效sourceID处理
$invalidComponent = new stdclass();
$invalidComponent->sourceID = 9999;
r($screenTest->buildChartTest($invalidComponent)) && p() && e('0'); // 步骤5：无效ID返回false