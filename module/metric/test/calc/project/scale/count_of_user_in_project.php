#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(200, false);
zdTable('team')->config('team', $useCommon = true, $levels = 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_user_in_project
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult()) && p('0:value') && e('37'); // 测试分组数。
