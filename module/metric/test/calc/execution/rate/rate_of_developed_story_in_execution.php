#!/usr/bin/env php
<?php

/**

title=rate_of_developed_story_in_execution
timeout=0
cid=1

- 测试分组数。 @6
- 测试项目2。第0条的value属性 @0.1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('project')->config('project_type', true, 4)->gen(100);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);
zdTable('projectstory')->config('executionstory', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('project' => '4'))) && p('0:value') && e('0.1');  // 测试项目2。