#!/usr/bin/env php
<?php

/**

title=count_of_merged_pull_requests_in_codebase
timeout=0
cid=1

- 测试合并请求数。第0条的value属性 @54

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('repo')->config('repo', true, 4)->gen(10);
zdTable('mr')->config('mr', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('codebase' => '3'))) && p('0:value') && e('54'); // 测试合并请求数。