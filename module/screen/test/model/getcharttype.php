#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getChartType();
timeout=0
cid=18240

- 执行screenTest模块的getChartTypeTest方法，参数是'Tables'  @pivot
- 执行screenTest模块的getChartTypeTest方法，参数是'pivot'  @pivot
- 执行screenTest模块的getChartTypeTest方法，参数是'Metrics'  @metric
- 执行screenTest模块的getChartTypeTest方法，参数是'chart'  @chart
- 执行screenTest模块的getChartTypeTest方法，参数是''  @chart

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

r($screenTest->getChartTypeTest('Tables')) && p() && e('pivot');
r($screenTest->getChartTypeTest('pivot')) && p() && e('pivot');
r($screenTest->getChartTypeTest('Metrics')) && p() && e('metric');
r($screenTest->getChartTypeTest('chart')) && p() && e('chart');
r($screenTest->getChartTypeTest('')) && p() && e('chart');