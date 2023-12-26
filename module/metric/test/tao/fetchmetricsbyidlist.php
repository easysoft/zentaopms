#!/usr/bin/env php
<?php
/**
title=fetchMetricsByIDList
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->fetchMetricsByIDList(array(1,2))) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program'); // 查询id为1，2的度量
r($metric->fetchMetricsByIDList('1,2')) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program');      // 查询id为1，2的度量
r($metric->fetchMetricsByIDList('122')) && p('0:id,purpose,code') && e('122,scale,count_of_daily_run_case');                                                // 查询id为122的度量
