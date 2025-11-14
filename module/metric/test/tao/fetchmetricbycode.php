#!/usr/bin/env php
<?php

/**

title=fetchMetricByCode
timeout=0
cid=17163

- 获取codeList[1]的id,scale,product
 - 属性id @13
 - 属性purpose @scale
 - 属性object @product
- 获取codeList[2]的id,scale,product
 - 属性id @145
 - 属性purpose @scale
 - 属性object @productplan
- 获取codeList[3]的id,scale,product
 - 属性id @205
 - 属性purpose @scale
 - 属性object @execution
- 获取codeList[4]的id,scale,product
 - 属性id @244
 - 属性purpose @scale
 - 属性object @story
- 获取codeList[5]的id,scale,product
 - 属性id @262
 - 属性purpose @scale
 - 属性object @bug

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

$codeList[1] = 'count_of_normal_product';
$codeList[2] = 'count_of_annual_finished_productplan_in_product';
$codeList[3] = 'count_of_closed_execution_in_project';
$codeList[4] = 'count_of_invalid_story_in_execution';
$codeList[5] = 'count_of_daily_fixed_bug_in_user';

r($metric->fetchMetricByCode($codeList[1])) && p('id,purpose,object') && e('13,scale,product');      // 获取codeList[1]的id,scale,product
r($metric->fetchMetricByCode($codeList[2])) && p('id,purpose,object') && e('146,scale,productplan'); // 获取codeList[2]的id,scale,product
r($metric->fetchMetricByCode($codeList[3])) && p('id,purpose,object') && e('206,scale,execution');   // 获取codeList[3]的id,scale,product
r($metric->fetchMetricByCode($codeList[4])) && p('id,purpose,object') && e('245,scale,story');       // 获取codeList[4]的id,scale,product
r($metric->fetchMetricByCode($codeList[5])) && p('id,purpose,object') && e('263,scale,bug');         // 获取codeList[5]的id,scale,product
