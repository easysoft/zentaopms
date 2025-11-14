#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchMetricsByCodeList();
timeout=0
cid=17169

- 执行metric模块的fetchMetricsByCodeList方法，参数是array 
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
 - 第1条的id属性 @2
 - 第1条的purpose属性 @scale
 - 第1条的code属性 @count_of_doing_program
- 执行metric模块的fetchMetricsByCodeList方法，参数是array 
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
- 执行metric模块的fetchMetricsByCodeList方法，参数是array  @0
- 执行metric模块的fetchMetricsByCodeList方法，参数是array  @0
- 执行metric模块的fetchMetricsByCodeList方法，参数是array 
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
 - 第1条的id属性 @2
 - 第1条的purpose属性 @scale
 - 第1条的code属性 @count_of_doing_program

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->fetchMetricsByCodeList(array('count_of_program', 'count_of_doing_program'))) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program');
r($metric->fetchMetricsByCodeList(array('count_of_program'))) && p('0:id,purpose,code') && e('1,scale,count_of_program');
r($metric->fetchMetricsByCodeList(array('non_existent_code'))) && p() && e('0');
r($metric->fetchMetricsByCodeList(array())) && p() && e('0');
r($metric->fetchMetricsByCodeList(array('count_of_program', 'non_existent_code', 'count_of_doing_program'))) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program');