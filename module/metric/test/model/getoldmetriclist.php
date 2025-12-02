#!/usr/bin/env php
<?php

/**

title=getOldMetricList
timeout=0
cid=17116

- 测试第10条数据
 - 第10条的scope属性 @project
 - 第10条的object属性 @task
 - 第10条的code属性 @meas10
- 测试第20条数据
 - 第20条的scope属性 @product
 - 第20条的object属性 @task
 - 第20条的code属性 @meas20
- 测试第30条数据
 - 第30条的scope属性 @execution
 - 第30条的object属性 @stage
 - 第30条的code属性 @meas30
- 测试第40条数据
 - 第40条的scope属性 @program
 - 第40条的object属性 @stage
 - 第40条的code属性 @meas40
- 测试第50条数据
 - 第50条的scope属性 @dept
 - 第50条的object属性 @finance
 - 第50条的code属性 @meas50

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

zenData('basicmeas')->loadYaml('meas', true)->gen(100);

$metric = new metricTest();

r($metric->getOldMetricList()) && p('10:scope,object,code') && e('project,task,meas10');    //测试第10条数据
r($metric->getOldMetricList()) && p('20:scope,object,code') && e('product,task,meas20');    //测试第20条数据
r($metric->getOldMetricList()) && p('30:scope,object,code') && e('execution,stage,meas30'); //测试第30条数据
r($metric->getOldMetricList()) && p('40:scope,object,code') && e('program,stage,meas40');   //测试第40条数据
r($metric->getOldMetricList()) && p('50:scope,object,code') && e('dept,finance,meas50');    //测试第50条数据
