#!/usr/bin/env php
<?php

/**

title=count_of_daily_code_commits_in_codebase
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

a($calc->getResult()); die;
r(count($calc->getResult()))            && p('')             && e('3');   // 测试分组数。
r($calc->getResult(array('repo' => 4))) && p('0:repo,value') && e('4,1'); // 测试代码库待处理问题数。
