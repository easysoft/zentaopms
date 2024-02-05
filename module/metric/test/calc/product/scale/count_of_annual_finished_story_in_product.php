#!/usr/bin/env php
<?php

/**

title=count_of_annual_finished_story_in_product
timeout=0
cid=1

- 测试分组数。 @7
- 测试2015年产品3关闭的研发需求数。第0条的value属性 @1
- 测试2019年产品5关闭的研发需求数。第0条的value属性 @1
- 测试已删除产品4关闭的研发需求数。第0条的value属性 @0
- 测试不存在的产品。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('7'); // 测试分组数。
r($calc->getResult(array('product' => '3',  'year' => '2015')))  && p('0:value') && e('1'); // 测试2015年产品3关闭的研发需求数。
r($calc->getResult(array('product' => '5',  'year' => '2019')))  && p('0:value') && e('1'); // 测试2019年产品5关闭的研发需求数。
r($calc->getResult(array('product' => '4',  'year' => '2019')))  && p('0:value') && e('0'); // 测试已删除产品4关闭的研发需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0'); // 测试不存在的产品。