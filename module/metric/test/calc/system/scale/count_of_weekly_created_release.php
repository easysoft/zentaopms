#!/usr/bin/env php
<?php

/**

title=count_of_weekly_created_release
timeout=0
cid=1

- 测试新增发布分组数。 @200
- 测试2019年3月月度新增发布数。第0条的value属性 @1
- 测试2019年9月月度新增发布数。第0条的value属性 @0
- 测试2020年5月月度新增发布数。第0条的value属性 @1
- 测试2020年12月月度新增发布数。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('200'); // 测试新增发布分组数。

r($calc->getResult(array('year' => '2019', 'week' => '03'))) && p('0:value') && e('1'); // 测试2019年3月月度新增发布数。
r($calc->getResult(array('year' => '2019', 'week' => '09'))) && p('0:value') && e('0'); // 测试2019年9月月度新增发布数。
r($calc->getResult(array('year' => '2020', 'week' => '05'))) && p('0:value') && e('1'); // 测试2020年5月月度新增发布数。
r($calc->getResult(array('year' => '2020', 'week' => '12'))) && p('0:value') && e('0'); // 测试2020年12月月度新增发布数。