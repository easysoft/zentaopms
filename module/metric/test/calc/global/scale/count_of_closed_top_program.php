#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('program', $useCommon = true, $levels = 4)->gen(356, true, false);

$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

/**

title=count_of_closed_top_program
timeout=0
cid=1

*/

r($calc->getResult()) && p('0:value') && e('45'); // 测试关闭的一级项目集数量
