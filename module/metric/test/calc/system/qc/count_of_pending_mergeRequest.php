#!/usr/bin/env php
<?php

/**

title=count_of_pending_mergeRequest
timeout=0
cid=1

- 测试合并数。第0条的value属性 @336

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('repo')->config('repo', true, 4)->gen(10);
zdTable('mr')->config('mr', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('336'); // 测试合并数。