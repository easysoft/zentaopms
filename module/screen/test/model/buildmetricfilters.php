#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';
zenData('metric')->gen(1);

/**

title=测试 screenModel->buildMetricFilters();
timeout=0
cid=1

*/

global $tester;
$screen = new screenTest();

$tester->loadModel('bi');
$metric1 = $tester->loadModel('metric')->getByID(1);
$metric1 = array_merge((array)$metric1, $config->bi->builtin->metrics[0]);

r($screen->buildMetricFilters((object)$metric1, false, false)) && p() && e(0);
r($screen->buildMetricFilters((object)$metric1, false, true))  && p('0:field') && e('date');

$metric1 = array_merge((array)$metric1, $config->bi->builtin->metrics[30]);
r($screen->buildMetricFilters((object)$metric1, false, true)) && p('0:field') && e('date');
r($screen->buildMetricFilters((object)$metric1, true, true))  && p('0:field') && e('system');
r($screen->buildMetricFilters((object)$metric1, true, true))  && p('1:field') && e('date');
