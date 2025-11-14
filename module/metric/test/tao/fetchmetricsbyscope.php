#!/usr/bin/env php
<?php

/**

title=fetchMetricsByScope
timeout=0
cid=17172

- 获取系统范围下的第一个度量项
 - 第system条的id属性 @143
 - 第system条的purpose属性 @rate
 - 第system条的code属性 @count_of_pending_deployment
- 获取产品范围下的第一个度量项
 - 第product条的id属性 @316
 - 第product条的purpose属性 @scale
 - 第product条的code属性 @scale_of_developed_story_in_product
- 获取系统度量项的个数 @1
- 获取系统度量项目的个数，限制limit @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->fetchMetricsByScope('system'))           && p('system:id,purpose,code')  && e('143,rate,count_of_pending_deployment');          // 获取系统范围下的第一个度量项
r($metric->fetchMetricsByScope('product'))          && p('product:id,purpose,code') && e('316,scale,scale_of_developed_story_in_product'); // 获取产品范围下的第一个度量项
r(count($metric->fetchMetricsByScope('system')))    && p()                          && e(1);                                               // 获取系统度量项的个数
r(count($metric->fetchMetricsByScope('system', 2))) && p()                          && e(1);                                               // 获取系统度量项目的个数，限制limit
