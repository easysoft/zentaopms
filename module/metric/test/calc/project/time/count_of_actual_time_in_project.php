#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_time', $useCommon = true, $levels = 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_actual_time_in_project
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('1000'); // 测试分组数。

r($calc->getResult(array('project' => '1804'))) && p('0:value') && e('540'); // 测试项目1804。
