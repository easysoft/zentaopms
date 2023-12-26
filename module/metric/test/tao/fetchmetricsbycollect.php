#!/usr/bin/env php
<?php

/**

title=fetchMetricsByCollect
timeout=0
cid=1

- 获取我收藏的全部度量项的第一个度量
 - 第0条的id属性 @10
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_annual_closed_top_program
- 获取我收藏的未发布度量项 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

su('admin');
$metric = new metricTest();

r($metric->fetchMetricsByCollect('all')) && p('0:id,purpose,code') && e('10,scale,count_of_annual_closed_top_program'); // 获取我收藏的全部度量项的第一个度量
r($metric->fetchMetricsByCollect('wait')) && p() && e('0');                                                             // 获取我收藏的未发布度量项