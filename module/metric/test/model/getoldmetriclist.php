#!/usr/bin/env php
<?php

/**

title=getOldMetricList
timeout=0
cid=1

- 执行metric模块的getOldMetricList方法 
 - 第10条的scope属性 @project
 - 第10条的object属性 @stage
 - 第10条的code属性 @pgmDevelPlanDays
- 执行metric模块的getOldMetricList方法 
 - 第40条的scope属性 @project
 - 第40条的object属性 @finance
 - 第40条的code属性 @pgmRequestRealHours

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getOldMetricList()) && p('10:scope,object,code') && e('project,stage,pgmDevelPlanDays');
r($metric->getOldMetricList()) && p('40:scope,object,code') && e('project,finance,pgmRequestRealHours');