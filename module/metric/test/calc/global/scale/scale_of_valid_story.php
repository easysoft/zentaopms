#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story', $useCommon = true, $levels = 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_invalid_story
timeout=0
cid=1

*/

r($calc->getResult()) && p('') && e('9'); // 测试按全局统计的有效研发需求规模数
