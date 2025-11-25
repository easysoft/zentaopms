#!/usr/bin/env php
<?php

/**

title=getOldMetricByID
timeout=0
cid=17115

- 测试第1个旧的度量项
 - 属性purpose @duration
 - 属性scope @project
 - 属性object @task
 - 属性code @meas1
- 测试第10个旧的度量项
 - 属性purpose @duration
 - 属性scope @project
 - 属性object @task
 - 属性code @meas10
- 测试第20个旧的度量项
 - 属性purpose @workload
 - 属性scope @product
 - 属性object @task
 - 属性code @meas20
- 测试第50个旧的度量项
 - 属性purpose @duration
 - 属性scope @dept
 - 属性object @finance
 - 属性code @meas50

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

zenData('basicmeas')->loadYaml('meas', true)->gen(100);

$metric = new metricTest();

r($metric->getOldMetricByID(1))  && p('purpose,scope,object,code') && e('duration,project,task,meas1');  //测试第1个旧的度量项
r($metric->getOldMetricByID(10)) && p('purpose,scope,object,code') && e('duration,project,task,meas10'); //测试第10个旧的度量项
r($metric->getOldMetricByID(20)) && p('purpose,scope,object,code') && e('workload,product,task,meas20'); //测试第20个旧的度量项
r($metric->getOldMetricByID(50)) && p('purpose,scope,object,code') && e('duration,dept,finance,meas50'); //测试第50个旧的度量项
