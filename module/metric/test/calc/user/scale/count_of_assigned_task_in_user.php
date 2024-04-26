#!/usr/bin/env php
<?php

/**

title=count_of_assigned_task_in_user
timeout=0
cid=1

- 测试分组数。 @8
- 测试用户dev。第0条的value属性 @22

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('project_status', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(20, false);
zendata('task')->loadYaml('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('8'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('22'); // 测试用户dev。