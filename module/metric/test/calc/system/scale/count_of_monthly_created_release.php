#!/usr/bin/env php
<?php

/**

title=count_of_monthly_created_release
timeout=0
cid=1

- 测试新增发布分组数。 @132
- 测试2019年3月月度新增发布数。第0条的value属性 @1
- 测试2019年9月月度新增发布数。第0条的value属性 @1
- 测试2020年5月月度新增发布数。第0条的value属性 @2
- 测试2020年12月月度新增发布数。第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('132'); // 测试新增发布分组数。

r($calc->getResult(array('year' => '2019', 'month' => '03'))) && p('0:value') && e('1'); // 测试2019年3月月度新增发布数。
r($calc->getResult(array('year' => '2019', 'month' => '09'))) && p('0:value') && e('1'); // 测试2019年9月月度新增发布数。
r($calc->getResult(array('year' => '2020', 'month' => '05'))) && p('0:value') && e('2'); // 测试2020年5月月度新增发布数。
r($calc->getResult(array('year' => '2020', 'month' => '12'))) && p('0:value') && e('1'); // 测试2020年12月月度新增发布数。