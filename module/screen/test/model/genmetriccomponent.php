#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genMetricComponent();
timeout=0
cid=18230

- 执行screenTest模块的genMetricComponentTest方法，参数是1 属性hasComponent @1
- 执行screenTest模块的genMetricComponentTest方法，参数是2 属性isDeleted @1
- 执行screenTest模块的genMetricComponentTest方法，参数是3 属性isWaiting @1
- 执行screenTest模块的genMetricComponentTest方法，参数是4, null 属性hasComponent @1
- 执行screenTest模块的genMetricComponentTest方法，参数是5, null, array 属性hasComponent @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('metric')->loadYaml('metric_genmetriccomponent', false, 2)->gen(10);
zenData('metriclib')->loadYaml('metriclib_genmetriccomponent', false, 2)->gen(50);

su('admin');

$screenTest = new screenTest();

r($screenTest->genMetricComponentTest(1)) && p('hasComponent') && e('1');
r($screenTest->genMetricComponentTest(2)) && p('isDeleted') && e('1');
r($screenTest->genMetricComponentTest(3)) && p('isWaiting') && e('1');
r($screenTest->genMetricComponentTest(4, null)) && p('hasComponent') && e('1');
r($screenTest->genMetricComponentTest(5, null, array('dateString' => '2023-01-01'))) && p('hasComponent') && e('1');