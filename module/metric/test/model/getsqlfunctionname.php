#!/usr/bin/env php
<?php
/**
title=getSqlFunctionName
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$measurementList = array('pgmURInitScale', 'pgmSRInitScale', 'pgmPlanScale', 'pgmRequestPlanDays', 'pgmTestRealDays', 'pgmRealEstHours');

r($metric->getSqlFunctionName($measurementList[0])) && p('') && e('qc_pgmurinitscale');     // 测试获取函数名pgmURInitScale
r($metric->getSqlFunctionName($measurementList[1])) && p('') && e('qc_pgmsrinitscale');     // 测试获取函数名pgmSRInitScale
r($metric->getSqlFunctionName($measurementList[2])) && p('') && e('qc_pgmplanscale');       // 测试获取函数名pgmPlanScale
r($metric->getSqlFunctionName($measurementList[3])) && p('') && e('qc_pgmrequestplandays'); // 测试获取函数名pgmRequestPlanDays
r($metric->getSqlFunctionName($measurementList[4])) && p('') && e('qc_pgmtestrealdays');    // 测试获取函数名pgmTestRealDays
r($metric->getSqlFunctionName($measurementList[5])) && p('') && e('qc_pgmrealesthours');    // 测试获取函数名pgmRealEstHours
