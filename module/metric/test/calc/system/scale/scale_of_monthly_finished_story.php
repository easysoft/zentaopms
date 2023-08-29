#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_monthly_finished_story
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('38'); // 测试按产品的月度完成需求分组数。

r($calc->getResult(array('year' => '2019', 'month' => '01'))) && p('0:value') && e('0'); // 测试2019年1月完成的需求数。
r($calc->getResult(array('year' => '2019', 'month' => '06'))) && p('0:value') && e('5'); // 测试2019年6月完成的需求数。
r($calc->getResult(array('year' => '2020', 'month' => '08'))) && p('0:value') && e('1'); // 测试2020年8月完成的需求数。
r($calc->getResult(array('year' => '2020', 'month' => '10'))) && p('0:value') && e('1'); // 测试2020年10月完成的需求数。
r($calc->getResult(array('year' => '2021', 'month' => '04'))) && p('')        && e('0'); // 测试不存在的产品的需求数。
