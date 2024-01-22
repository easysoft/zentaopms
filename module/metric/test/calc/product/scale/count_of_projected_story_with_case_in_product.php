#!/usr/bin/env php
<?php

/**

title=count_of_projected_story_with_case_in_product
timeout=0
cid=1

- 测试分组数。 @5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);
zdTable('projectstory')->config('projectstory', true, 4)->gen(1000);
zdTable('case')->config('case', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。