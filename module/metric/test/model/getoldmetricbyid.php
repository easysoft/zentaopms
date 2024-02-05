#!/usr/bin/env php
<?php

/**

title=getOldMetricByID
timeout=0
cid=1

- 执行metric模块的getOldMetricByID方法，参数是10 
 - 属性purpose @duration
 - 属性scope @project
 - 属性object @stage
 - 属性code @pgmDevelPlanDays
- 执行metric模块的getOldMetricByID方法，参数是40 
 - 属性purpose @workload
 - 属性scope @project
 - 属性object @finance
 - 属性code @pgmRequestRealHours
- 执行metric模块的getOldMetricByID方法，参数是45 
 - 属性purpose @workload
 - 属性scope @project
 - 属性object @finance
 - 属性code @pgmDesignFirstEstHours

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getOldMetricByID(10)) && p('purpose,scope,object,code') && e('duration,project,stage,pgmDevelPlanDays');
r($metric->getOldMetricByID(40)) && p('purpose,scope,object,code') && e('workload,project,finance,pgmRequestRealHours');
r($metric->getOldMetricByID(45)) && p('purpose,scope,object,code') && e('workload,project,finance,pgmDesignFirstEstHours');