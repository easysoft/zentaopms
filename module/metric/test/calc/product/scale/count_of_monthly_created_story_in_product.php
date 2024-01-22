#!/usr/bin/env php
<?php

/**

title=count_of_monthly_created_story_in_product
timeout=0
cid=1

- 测试2017年4月产品7关闭的需求数。第0条的value属性 @2
- 测试2017年5月产品7关闭的需求数。第0条的value属性 @2
- 测试2013年3月产品9关闭的需求数。第0条的value属性 @9
- 测试2013年4月产品9关闭的需求数。第0条的value属性 @2
- 测试已删除产品8关闭的需求数。第0条的value属性 @0
- 测试不存在的产品的需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '7',  'year' => '2019', 'month' => '10')))  && p('0:value') && e('2'); // 测试2017年4月产品7关闭的需求数。
r($calc->getResult(array('product' => '7',  'year' => '2019', 'month' => '12')))  && p('0:value') && e('2'); // 测试2017年5月产品7关闭的需求数。
r($calc->getResult(array('product' => '9',  'year' => '2012', 'month' => '03')))  && p('0:value') && e('9'); // 测试2013年3月产品9关闭的需求数。
r($calc->getResult(array('product' => '9',  'year' => '2012', 'month' => '04')))  && p('0:value') && e('2'); // 测试2013年4月产品9关闭的需求数。
r($calc->getResult(array('product' => '8',  'year' => '2013', 'month' => '04')))  && p('0:value') && e('0'); // 测试已删除产品8关闭的需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021', 'month' => '04'))) && p('')        && e('0'); // 测试不存在的产品的需求数。