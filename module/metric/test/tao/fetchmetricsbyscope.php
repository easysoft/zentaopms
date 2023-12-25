#!/usr/bin/env php
<?php
/**
title=fetchMetricsByScope
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->fetchMetricsByScope('system')) && p('0:id,purpose,code') && e('1,scale,count_of_program');                   // 获取系统范围下的第一个度量项
r($metric->fetchMetricsByScope('product')) && p('0:id,purpose,code') && e('135,scale,count_of_productplan_in_product'); // 获取产品范围下的第一个度量项
r(count($metric->fetchMetricsByScope('system'))) && p() && e(130);                                                      // 获取系统度量项的个数
r(count($metric->fetchMetricsByScope('system', 2))) && p() && e(2);                                                     // 获取系统度量项目的个数，限制limit
