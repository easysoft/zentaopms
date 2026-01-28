#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildTableChart();
timeout=0
cid=18219

- 执行screenTest模块的buildTableChartTest方法，参数是$component1, $chart1 属性key @TableScrollBoard
- 执行$result2->chartConfig @1
- 执行$result3->option @1
- 执行screenTest模块的buildTableChartTest方法，参数是$component4, $chart4 属性key @TableScrollBoard
- 执行screenTest模块的buildTableChartTest方法，参数是$component5, $chart5 属性key @TableScrollBoard

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$screen = zenData('screen');
$screen->id->range('1-5');
$screen->name->range('测试大屏{1-5}');
$screen->status->range('published');
$screen->deleted->range('0');
$screen->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$screenTest = new screenModelTest();

// 5. 测试步骤1：传入无设置的chart对象，验证返回默认配置key
$component1 = new stdclass();
$component1->option = new stdclass();
$chart1 = new stdclass();
$chart1->settings = null;
r($screenTest->buildTableChartTest($component1, $chart1)) && p('key') && e('TableScrollBoard');

// 测试步骤2：传入无设置的chart对象，验证返回chartConfig属性存在
$component2 = new stdclass();
$component2->option = new stdclass();
$chart2 = new stdclass();
$chart2->settings = null;
$result2 = $screenTest->buildTableChartTest($component2, $chart2);
r(isset($result2->chartConfig)) && p('') && e('1');

// 测试步骤3：传入无设置的chart对象，验证返回option属性存在
$component3 = new stdclass();
$component3->option = new stdclass();
$chart3 = new stdclass();
$chart3->settings = null;
$result3 = $screenTest->buildTableChartTest($component3, $chart3);
r(isset($result3->option)) && p('') && e('1');

// 测试步骤4：传入无设置的chart对象，验证返回key值
$component4 = new stdclass();
$component4->option = new stdclass();
$chart4 = new stdclass();
$chart4->settings = null;
r($screenTest->buildTableChartTest($component4, $chart4)) && p('key') && e('TableScrollBoard');

// 测试步骤5：传入无设置的chart对象，验证key属性存在
$component5 = new stdclass();
$component5->option = new stdclass();
$chart5 = new stdclass();
$chart5->settings = null;
r($screenTest->buildTableChartTest($component5, $chart5)) && p('key') && e('TableScrollBoard');