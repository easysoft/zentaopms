#!/usr/bin/env php
<?php

/**

title=count_of_annual_finished_story_in_project
timeout=0
cid=1

- 测试分组数。 @18
- 测试项目2。第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);
zdTable('projectstory')->config('projectstory', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('18'); // 测试分组数。

r($calc->getResult(array('project' => '9', 'year' => '2018'))) && p('0:value') && e('3');  // 测试项目2。