#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_case
timeout=0
cid=1

- 测试年度新增用例分组数。 @11
- 测试2017年用例Bug数第0条的value属性 @22
- 测试2018年用例Bug数第0条的value属性 @9
- 测试2023年用例Bug数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('case')->config('case', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11');                        // 测试年度新增用例分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('22'); // 测试2017年用例Bug数
r($calc->getResult(array('year' => '2018'))) && p('0:value') && e('9');  // 测试2018年用例Bug数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年用例Bug数