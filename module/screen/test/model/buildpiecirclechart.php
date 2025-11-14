#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildPieCircleChart();
timeout=0
cid=18216

- 测试步骤1：无settings时的默认配置属性key @PieCircle
- 测试步骤2：组件状态验证属性status @normal
- 测试步骤3：option类型验证 @nomal
- 测试步骤4：chartKey验证 @VPieCircle
- 测试步骤5：request类型验证 @get

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 简化数据生成，避免zenData错误

su('admin');

$screenTest = new screenTest();

// 准备测试组件
$component = new stdclass();
$component->option = new stdclass();

// 准备测试chart对象 - 无settings的情况
$chartNoSettings = new stdclass();
$chartNoSettings->settings = '';
$chartNoSettings->sql = '';
$chartNoSettings->driver = '';

// 准备测试chart对象 - 有settings的情况
$chartWithSettings = new stdclass();
$chartWithSettings->settings = '{"metric":[{"agg":"count"}],"group":[{"field":"status"}]}';
$chartWithSettings->sql = 'select status from zt_task';
$chartWithSettings->driver = 'mysql';

$result = $screenTest->buildPieCircleChartTest($component, $chartNoSettings);

r($result) && p('key') && e('PieCircle'); // 测试步骤1：无settings时的默认配置
r($result) && p('status') && e('normal'); // 测试步骤2：组件状态验证
r($result->option->type) && p('') && e('nomal'); // 测试步骤3：option类型验证
r($result->chartConfig->chartKey) && p('') && e('VPieCircle'); // 测试步骤4：chartKey验证
r($result->request->requestHttpType) && p('') && e('get'); // 测试步骤5：request类型验证