#!/usr/bin/env php
<?php

/**

title=cv_in_waterfall
timeout=0
cid=1

- 测试分组数。 @5
- 测试项目7。第0条的value属性 @-0.7799

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('waterfall', true, 4)->gen(10);
zdTable('project')->config('stage', true, 4)->gen(40, false);
zdTable('task')->config('task_waterfall', true, 4)->gen(1000);
zdTable('effort')->config('effort', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。

r($calc->getResult(array('project' => '7'))) && p('0:value') && e('-0.7799'); // 测试项目7。