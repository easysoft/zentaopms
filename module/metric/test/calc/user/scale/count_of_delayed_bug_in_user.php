#!/usr/bin/env php
<?php
/**
title=count_of_delayed_bug_in_user
timeout=0
cid=1
*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('project_status', $useCommon = true, $levels = 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('4'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('6'); // 测试用户dev。
