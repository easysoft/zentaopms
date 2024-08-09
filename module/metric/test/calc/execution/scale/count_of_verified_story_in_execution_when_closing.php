#!/usr/bin/env php
<?php

/**

title=count_of_finished_story_in_execution
timeout=0
cid=1

- 测试分组数。 @6
- 测试按执行4统计的执行关闭时验收通过的研发需求数7第0条的value属性 @7
- 测试按执行5统计的执行关闭时验收通过的研发需求数7第0条的value属性 @7
- 测试按执行5统计的执行关闭时验收通过的研发需求数7第0条的value属性 @7

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project_type', true, 4)->gen(100);
zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(1000);
zendata('projectstory')->loadYaml('executionstory', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('project' => '4')))  && p('0:value') && e('7');  // 测试按执行4统计的执行关闭时验收通过的研发需求数7
r($calc->getResult(array('project' => '5')))  && p('0:value') && e('7');  // 测试按执行5统计的执行关闭时验收通过的研发需求数7
r($calc->getResult(array('project' => '10'))) && p('0:value') && e('7');  // 测试按执行5统计的执行关闭时验收通过的研发需求数7