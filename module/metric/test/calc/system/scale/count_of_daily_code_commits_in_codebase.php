#!/usr/bin/env php
<?php

/**

title=count_of_daily_code_commits_in_codebase
timeout=0
cid=1

- 测试分组数。 @1
- 测试代码库提交数。
 - 第0条的repo属性 @1
 - 第0条的value属性 @4

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('pipeline')->gen(5);
zendata('repo')->loadYaml('repo', true, 4)->gen(1);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

$options = array(
    'year'  => '2023',
    'month' => '12',
    'day'   => '23,24'
);
$result = $calc->getResult($options);
r(count($result)) && p('')             && e('1');   // 测试分组数。
r($result)        && p('0:repo,value') && e('1,4'); // 测试代码库提交数。