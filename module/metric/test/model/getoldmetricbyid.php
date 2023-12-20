#!/usr/bin/env php
<?php
/**
title=getOldMetricByID
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getOldMetricByID(10)) && p('purpose,scope,object,code') && e('duration,project,stage,pgmDevelPlanDays');
r($metric->getOldMetricByID(40)) && p('purpose,scope,object,code') && e('workload,project,finance,pgmRequestRealHours');
r($metric->getOldMetricByID(45)) && p('purpose,scope,object,code') && e('workload,project,finance,pgmDesignFirstEstHours');

