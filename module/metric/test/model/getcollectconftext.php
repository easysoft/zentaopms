#!/usr/bin/env php
<?php
/**
title=getCollectConfText
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$collectConfList = array();
$collectConfList[0] = '{"week":"1,2,3,4,5,6,0","type":"week"}';
$collectConfList[1] = '{"week":"1,2,3","type":"week"}';
$collectConfList[2] = '{"week":"1,2,6,0","type":"week"}';
$collectConfList[3] = '{"month":"1,2,5,11","type":"month"}';
$collectConfList[4] = '{"month":"1,2,3,4,5,6","type":"month"}';
$collectConfList[5] = '{"month":"11,12,13","type":"month"}';

$metricList = array();
$metricList[0] = (object)array('collectConf' => json_decode($collectConfList[0]), 'execTime' => '2023-1-1 10:10:10');
$metricList[1] = (object)array('collectConf' => json_decode($collectConfList[1]), 'execTime' => '2023-1-1 10:10:10');
$metricList[2] = (object)array('collectConf' => json_decode($collectConfList[2]), 'execTime' => '2023-1-1 10:10:10');
$metricList[3] = (object)array('collectConf' => json_decode($collectConfList[3]), 'execTime' => '2023-1-1 10:10:10');
$metricList[4] = (object)array('collectConf' => json_decode($collectConfList[4]), 'execTime' => '2023-1-1 10:10:10');
$metricList[5] = (object)array('collectConf' => json_decode($collectConfList[5]), 'execTime' => '2023-1-1 10:10:10');

r($metric->getCollectConfText($metricList[0])) && p('') && e('每周的星期一,星期二,星期三,星期四,星期五,星期六,星期日的2023-1-1 10:10:10'); // 测试week标签1
r($metric->getCollectConfText($metricList[1])) && p('') && e('每周的星期一,星期二,星期三的2023-1-1 10:10:10');                             // 测试week标签1
r($metric->getCollectConfText($metricList[2])) && p('') && e('每周的星期一,星期二,星期六,星期日的2023-1-1 10:10:10');                      // 测试week标签3
r($metric->getCollectConfText($metricList[3])) && p('') && e('每月的1号,2号,5号,11号的2023-1-1 10:10:10');                                 // 测试month标签1
r($metric->getCollectConfText($metricList[4])) && p('') && e('每月的1号,2号,3号,4号,5号,6号的2023-1-1 10:10:10');                          // 测试month标签2
r($metric->getCollectConfText($metricList[5])) && p('') && e('每月的11号,12号,13号的2023-1-1 10:10:10');                                   // 测试month标签3
