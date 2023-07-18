#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_monthly_finished_story
timeout=0
cid=1

- 测试按产品的月度完成需求分组数。 @120

- 测试2017年4月完成的需求数。第0条的value属性 @3

- 测试2017年5月完成的需求数。第0条的value属性 @3

- 测试2013年3月完成的需求数。第0条的value属性 @4

- 测试2013年4月完成的需求数。第0条的value属性 @3

- 测试不存在的产品的需求数。 @0

*/

r(count($calc->getResult())) && p('') && e('120'); // 测试按产品的月度完成需求分组数。

r($calc->getResult(array('year' => '2019', 'month' => '07'))) && p('0:value') && e('3'); // 测试2017年4月完成的需求数。
r($calc->getResult(array('year' => '2019', 'month' => '10'))) && p('0:value') && e('3'); // 测试2017年5月完成的需求数。
r($calc->getResult(array('year' => '2020', 'month' => '02'))) && p('0:value') && e('4'); // 测试2013年3月完成的需求数。
r($calc->getResult(array('year' => '2020', 'month' => '03'))) && p('0:value') && e('3'); // 测试2013年4月完成的需求数。
r($calc->getResult(array('year' => '2021', 'month' => '04'))) && p('')        && e('0'); // 测试不存在的产品的需求数。