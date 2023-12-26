#!/usr/bin/env php
<?php

/**

title=rate_of_annual_delivered_story
timeout=0
cid=1

- 测试2011年研发需求交付率。第0条的value属性 @1
- 测试2014年研发需求交付率。第0条的value属性 @1
- 测试2015年研发需求交付率。第0条的value属性 @1
- 测试错误的年份。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('1'); // 测试2011年研发需求交付率。
r($calc->getResult(array('year' => '2014'))) && p('0:value') && e('1'); // 测试2014年研发需求交付率。
r($calc->getResult(array('year' => '2015'))) && p('0:value') && e('1'); // 测试2015年研发需求交付率。
r($calc->getResult(array('year' => '2099'))) && p('') && e('0'); // 测试错误的年份。