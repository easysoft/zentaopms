#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(20);
zdTable('issue')->config('issue', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_opened_issue_in_project
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('12'); // 测试分组数。

r($calc->getResult(array('project' => '2'))) && p('0:value') && e('15'); // 测试项目2。
