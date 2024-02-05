#!/usr/bin/env php
<?php

/**

title=fetchMetricByID
timeout=0
cid=1

- 获取codeList[1]的范围 @system
- 获取codeList[2]的范围 @product
- 获取codeList[3]的范围 @project
- 获取codeList[4]的范围 @execution
- 获取codeList[5]的范围 @user

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$codeList[1] = 'count_of_normal_product';
$codeList[2] = 'count_of_annual_finished_productplan_in_product';
$codeList[3] = 'count_of_closed_execution_in_project';
$codeList[4] = 'count_of_invalid_story_in_execution';
$codeList[5] = 'count_of_daily_fixed_bug_in_user';
r($metric->fetchMetricByID($codeList[1])) && p() && e('system');    // 获取codeList[1]的范围
r($metric->fetchMetricByID($codeList[2])) && p() && e('product');   // 获取codeList[2]的范围
r($metric->fetchMetricByID($codeList[3])) && p() && e('project');   // 获取codeList[3]的范围
r($metric->fetchMetricByID($codeList[4])) && p() && e('execution'); // 获取codeList[4]的范围
r($metric->fetchMetricByID($codeList[5])) && p() && e('user');      // 获取codeList[5]的范围