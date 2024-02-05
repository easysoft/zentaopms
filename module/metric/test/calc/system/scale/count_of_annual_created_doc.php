#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_doc
timeout=0
cid=1

- 测试年度新增文档分组数。 @11
- 测试2017年新增文档数第0条的value属性 @19
- 测试2018年新增文档数第0条的value属性 @18
- 测试2023年新增文档数第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('doc')->config('doc', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('11');                        // 测试年度新增文档分组数。
r($calc->getResult(array('year' => '2017'))) && p('0:value') && e('19'); // 测试2017年新增文档数
r($calc->getResult(array('year' => '2018'))) && p('0:value') && e('18'); // 测试2018年新增文档数
r($calc->getResult(array('year' => '2023'))) && p('0:value') && e('0');  // 测试2023年新增文档数