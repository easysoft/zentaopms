#!/usr/bin/env php
<?php

/**

title=scale_of_annual_delivered_story
timeout=0
cid=1

- 测试分组数 @10
- 测试2019年交付的研发需求规模。第0条的value属性 @49
- 测试2019年交付的研发需求规模。第0条的value属性 @49
- 测试2020年交付的研发需求规模。第0条的value属性 @33
- 测试2020年交付的研发需求规模。第0条的value属性 @33
- 测试不存在的产品的需求规模。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数

r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('49'); // 测试2019年交付的研发需求规模。
r($calc->getResult(array('year' => '2019'))) && p('0:value') && e('49'); // 测试2019年交付的研发需求规模。
r($calc->getResult(array('year' => '2020'))) && p('0:value') && e('33'); // 测试2020年交付的研发需求规模。
r($calc->getResult(array('year' => '2020'))) && p('0:value') && e('33'); // 测试2020年交付的研发需求规模。
r($calc->getResult(array('year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品的需求规模。