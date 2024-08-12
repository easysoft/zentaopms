#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_user
timeout=0
cid=1

- 测试年度新增用户分组数。 @5
- 测试2011年新增用户数第0条的value属性 @5
- 测试2013年新增用户数第0条的value属性 @4
- 测试2015年新增用户数第0条的value属性 @4

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('user')->loadYaml('user', true, 4)->gen(41);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5');                        // 测试年度新增用户分组数。
r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('5'); // 测试2011年新增用户数
r($calc->getResult(array('year' => '2013'))) && p('0:value') && e('4'); // 测试2013年新增用户数
r($calc->getResult(array('year' => '2015'))) && p('0:value') && e('4'); // 测试2015年新增用户数