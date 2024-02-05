#!/usr/bin/env php
<?php

/**

title=fetchMetricsByIDList
timeout=0
cid=1

- 查询id为1，2的度量
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
 - 第1条的id属性 @2
 - 第1条的purpose属性 @scale
 - 第1条的code属性 @count_of_doing_program
- 查询id为1，2的度量
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
 - 第1条的id属性 @2
 - 第1条的purpose属性 @scale
 - 第1条的code属性 @count_of_doing_program
- 查询id为122的度量
 - 第0条的id属性 @122
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_daily_run_case

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->fetchMetricsByIDList(array(1,2))) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program'); // 查询id为1，2的度量
r($metric->fetchMetricsByIDList('1,2')) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program');      // 查询id为1，2的度量
r($metric->fetchMetricsByIDList('122')) && p('0:id,purpose,code') && e('122,scale,count_of_daily_run_case');                                                // 查询id为122的度量