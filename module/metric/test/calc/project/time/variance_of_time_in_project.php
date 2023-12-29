#!/usr/bin/env php
<?php

/**

title=variance_of_time_in_project
timeout=0
cid=1

- 测试分组数。 @1000
- 测试项目1804。第0条的value属性 @336

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_time', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1000'); // 测试分组数。

r($calc->getResult(array('project' => '1804'))) && p('0:value') && e('336'); // 测试项目1804。