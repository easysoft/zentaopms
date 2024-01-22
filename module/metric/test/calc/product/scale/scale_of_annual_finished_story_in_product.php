#!/usr/bin/env php
<?php

/**

title=scale_of_annual_finished_story_in_product
timeout=0
cid=1

- 测试按产品的年度完成需求分组数。 @20
- 测试2016年产品3关闭的需求规模数。第0条的value属性 @0
- 测试2017年产品3关闭的需求规模数。第0条的value属性 @10
- 测试2016年产品5关闭的需求规模数。第0条的value属性 @54
- 测试2017年产品5关闭的需求规模数。第0条的value属性 @36
- 测试已删除产品4关闭的需求规模数。第0条的value属性 @0
- 测试不存在的产品的需求规模数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('20'); // 测试按产品的年度完成需求分组数。

r($calc->getResult(array('product' => '3',  'year' => '2016')))  && p('0:value') && e('0');  // 测试2016年产品3关闭的需求规模数。
r($calc->getResult(array('product' => '3',  'year' => '2017')))  && p('0:value') && e('10'); // 测试2017年产品3关闭的需求规模数。
r($calc->getResult(array('product' => '5',  'year' => '2016')))  && p('0:value') && e('54'); // 测试2016年产品5关闭的需求规模数。
r($calc->getResult(array('product' => '5',  'year' => '2017')))  && p('0:value') && e('36'); // 测试2017年产品5关闭的需求规模数。
r($calc->getResult(array('product' => '4',  'year' => '2017')))  && p('0:value') && e('0');  // 测试已删除产品4关闭的需求规模数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品的需求规模数。