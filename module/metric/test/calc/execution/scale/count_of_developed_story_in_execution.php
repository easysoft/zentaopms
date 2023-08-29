#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(100);
zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(1000);
zdTable('projectstory')->config('executionstory', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_developed_story_in_execution
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('project' => '4'))) && p('0:value') && e('1');  // 测试项目2。
