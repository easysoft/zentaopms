#!/usr/bin/env php
<?php

/**

title=getControlOptions
timeout=0
cid=1

- 测试product对象数 @5
- 测试传入错误参数的结果 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

$metric = new metricTest();

zenData('user')->loadYaml('user', true)->gen(30);
zenData('product')->loadYaml('product', true)->gen(10);

r(count($metric->getControlOptions('product')))   && p('') && e('5');  // 测试product对象数
r(count($metric->getControlOptions('waterfall'))) && p('') && e('1');  // 测试传入错误参数的结果
