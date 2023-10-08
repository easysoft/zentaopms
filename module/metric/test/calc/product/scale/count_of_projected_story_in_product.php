#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(1000);
zdTable('projectstory')->config('projectstory', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_projected_story_in_product
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。
