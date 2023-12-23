#!/usr/bin/env php
<?php
/**

title=count_of_submitted_code_issues_in_codebase
timeout=0
cid=1

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->config('bug_repo', true, 4)->gen(15);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))            && p()          && e('6'); // 测试分组数。
r($calc->getResult(array('repo' => 2))) && p('0:value') && e('2'); // 测试代码库ID是2的问题数。
