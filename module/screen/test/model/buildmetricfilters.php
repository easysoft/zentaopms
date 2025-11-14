#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';
zenData('metric')->gen(1);

/**

title=测试 screenModel->buildMetricFilters();
timeout=0
cid=18213

- 测试传入metric属性,isObjectMetric和isDateMetric为false的情况下，生成的值是否正确; @0
- 测试传入metric属性,isObjectMetric为false,isDateMetric为true的情况下，生成的值是否正确;第0条的field属性 @date
- 测试传入metric属性,isObjectMetric为false,isDateMetric为true的情况下，生成的值是否正确;第0条的field属性 @date
- 测试传入metric属性,isObjectMetric和isDateMetric为true的情况下，生成的第一条数据值是否正确;第0条的field属性 @system
- 测试传入metric属性,isObjectMetric和isDateMetric为true的情况下，生成的第二条数据值是否正确;第1条的field属性 @date

*/

global $tester;
$screen = new screenTest();

$tester->loadModel('bi');
$metric1 = $tester->loadModel('metric')->getByID(1);
$metric1 = array_merge((array)$metric1, $config->bi->builtin->metrics[0]);

r($screen->buildMetricFilters((object)$metric1, false, false)) && p() && e(0);               // 测试传入metric属性,isObjectMetric和isDateMetric为false的情况下，生成的值是否正确;
r($screen->buildMetricFilters((object)$metric1, false, true))  && p('0:field') && e('date'); // 测试传入metric属性,isObjectMetric为false,isDateMetric为true的情况下，生成的值是否正确;

$metric1 = array_merge((array)$metric1, $config->bi->builtin->metrics[30]);
r($screen->buildMetricFilters((object)$metric1, false, true)) && p('0:field') && e('date');   // 测试传入metric属性,isObjectMetric为false,isDateMetric为true的情况下，生成的值是否正确;
r($screen->buildMetricFilters((object)$metric1, true, true))  && p('0:field') && e('system'); // 测试传入metric属性,isObjectMetric和isDateMetric为true的情况下，生成的第一条数据值是否正确;
r($screen->buildMetricFilters((object)$metric1, true, true))  && p('1:field') && e('date');   // 测试传入metric属性,isObjectMetric和isDateMetric为true的情况下，生成的第二条数据值是否正确;
