#!/usr/bin/env php
<?php

/**

title=count_of_bug_in_project
timeout=0
cid=1

- 测试分组数。 @6
- 测试项目1的bug数第0条的value属性 @72
- 测试项目2的bug数第0条的value属性 @48
- 测试项目5的bug数第0条的value属性 @48
- 测试项目6的bug数第0条的value属性 @48

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(10);
zdTable('product')->config('product_shadow', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p() && e('6'); // 测试分组数。

r($calc->getResult(array('project' => 1))) && p('0:value') && e('72'); // 测试项目1的bug数
r($calc->getResult(array('project' => 2))) && p('0:value') && e('48'); // 测试项目2的bug数
r($calc->getResult(array('project' => 5))) && p('0:value') && e('48'); // 测试项目5的bug数
r($calc->getResult(array('project' => 6))) && p('0:value') && e('48'); // 测试项目6的bug数