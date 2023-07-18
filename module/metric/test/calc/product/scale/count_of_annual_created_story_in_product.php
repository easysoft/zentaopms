#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->gen(100);
zdTable('product')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_created_story_in_product
timeout=0
cid=1

*/

r(count($calc->getResult()))                                            && p('')        && e('25');                    // 测试分组数.
r($calc->getResult(array('product' => '1,2,3', 'year' => '2022,2023'))) && p('0:value;1:value;2:value') && e('4,4,4'); // 测试2023年产品1，2，3创建的需求数。
r($calc->getResult(array('product' => '4,5,6', 'year' => '2022,2023'))) && p('0:value;1:value;2:value') && e('4,4,4'); // 测试2023年产品4，5，6创建的需求数。
r($calc->getResult(array('product' => '1,2,3', 'year' => '2022')))      && p('')        && e('0');                     // 测试不存在的年份创建的需求数。
