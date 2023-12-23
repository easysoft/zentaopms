#!/usr/bin/env php
<?php
/**
title=getOldMetricList
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getOldMetricList()) && p('10:scope,object,code') && e('project,stage,pgmDevelPlanDays');
r($metric->getOldMetricList()) && p('40:scope,object,code') && e('project,finance,pgmRequestRealHours');
