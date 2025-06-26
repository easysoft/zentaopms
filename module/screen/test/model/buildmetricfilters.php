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
