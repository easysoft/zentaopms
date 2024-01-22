#!/usr/bin/env php
<?php

/**

title=count_of_pending_issue
timeout=0
cid=1

- 测试代码库待处理问题数。第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->config('bug_repo', true, 4)->gen(10);
zdTable('repo')->config('repo', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('3'); // 测试代码库待处理问题数。