#!/usr/bin/env php
<?php

/**

title=scale_of_annual_closed_story_in_product
timeout=0
cid=1

- 测试2012年产品7关闭的需求规模数。第0条的value属性 @180
- 测试2015年产品7关闭的需求规模数。第0条的value属性 @208
- 测试2011年产品9关闭的需求规模数。第0条的value属性 @276
- 测试2012年产品9关闭的需求规模数。第0条的value属性 @216
- 测试已删除产品关闭的需求规模数。第0条的value属性 @0
- 测试不存在的产品的需求规模数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(3000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '7',  'year' => '2012')))  && p('0:value') && e('180'); // 测试2012年产品7关闭的需求规模数。
r($calc->getResult(array('product' => '7',  'year' => '2015')))  && p('0:value') && e('208'); // 测试2015年产品7关闭的需求规模数。
r($calc->getResult(array('product' => '9',  'year' => '2011')))  && p('0:value') && e('276'); // 测试2011年产品9关闭的需求规模数。
r($calc->getResult(array('product' => '9',  'year' => '2012')))  && p('0:value') && e('216'); // 测试2012年产品9关闭的需求规模数。
r($calc->getResult(array('product' => '8',  'year' => '2012')))  && p('0:value') && e('0');   // 测试已删除产品关闭的需求规模数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。