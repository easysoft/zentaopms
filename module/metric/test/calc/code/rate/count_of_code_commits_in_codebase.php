#!/usr/bin/env php
<?php

/**

title=count_of_code_commits_in_codebase
timeout=0
cid=1

- 测试提交数。第0条的value属性 @10000

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('repo')->config('repo', true, 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('codebase' => '52'))) && p('0:value') && e('10000'); // 测试提交数。