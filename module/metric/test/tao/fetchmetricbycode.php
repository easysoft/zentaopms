#!/usr/bin/env php
<?php
/**
title=fetchMetricByCode
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$codeList[1] = 'count_of_normal_product';
$codeList[2] = 'count_of_annual_finished_productplan_in_product';
$codeList[3] = 'count_of_closed_execution_in_project';
$codeList[4] = 'count_of_invalid_story_in_execution';
$codeList[5] = 'count_of_daily_fixed_bug_in_user';

r($metric->fetchMetricByCode($codeList[1])) && p('id,purpose,object') && e('13,scale,product');      // 获取codeList[1]的id,scale,product
r($metric->fetchMetricByCode($codeList[2])) && p('id,purpose,object') && e('137,scale,productplan'); // 获取codeList[2]的id,scale,product
r($metric->fetchMetricByCode($codeList[3])) && p('id,purpose,object') && e('197,scale,execution');   // 获取codeList[3]的id,scale,product
r($metric->fetchMetricByCode($codeList[4])) && p('id,purpose,object') && e('236,scale,story');       // 获取codeList[4]的id,scale,product
r($metric->fetchMetricByCode($codeList[5])) && p('id,purpose,object') && e('254,scale,bug');         // 获取codeList[5]的id,scale,product
