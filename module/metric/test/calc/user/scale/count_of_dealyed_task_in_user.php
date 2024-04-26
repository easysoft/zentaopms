#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zendata('project')->loadYaml('project_status', $useCommon = true, $levels = 4)->gen(10);
zendata('project')->loadYaml('execution', $useCommon = true, $levels = 4)->gen(20, false);
zendata('task')->loadYaml('task', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_dealyed_task_in_user
timeout=0
cid=1

- 测试分组数。 @8
- 测试用户dev。第0条的value属性 @34

*/

r(count($calc->getResult())) && p('') && e('8'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('34'); // 测试用户dev。