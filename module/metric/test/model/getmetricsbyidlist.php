#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

/**

title=getMetricsByIDList
cid=1
pid=1

*/

r($metric->getMetricsByIDList('1,2')) && p('1:code') && e('count_of_doing_program'); // 使用字符串传入获取metric
r($metric->getMetricsByIDList(array(1,2))) && p('0:code') && e('count_of_program');  // 使用数组传入获取metric
r($metric->getMetricsByIDList('')) && p() && e('0');                                 // 传入一个空字符串没有metric返回
