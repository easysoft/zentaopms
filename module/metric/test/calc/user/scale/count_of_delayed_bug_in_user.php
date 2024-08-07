#!/usr/bin/env php
<?php

/**

title=count_of_delayed_bug_in_user
timeout=0
cid=1

- 测试分组数。 @4
- 测试用户dev。第0条的value属性 @18

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product_shadow', $useCommon = true, $levels = 4)->gen(10);
zendata('bug')->loadYaml('bug_resolution_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('4'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('18'); // 测试用户dev。