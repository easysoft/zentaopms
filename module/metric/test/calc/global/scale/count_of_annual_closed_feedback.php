#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(100);
zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_closed_feedback
timeout=0
cid=1

*/

r(count($calc->getResult())) && p('') && e('3'); // 测试分组数。

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('1'); // 测试2011年关闭的反馈数。
r($calc->getResult(array('year' => '2012'))) && p('0:value') && e('1'); // 测试2012年关闭的反馈数。
r($calc->getResult(array('year' => '9999'))) && p('')        && e('0'); // 测试不存在年份的反馈数。

