#!/usr/bin/env php
<?php

/**

title=fetchModules
timeout=0
cid=1

- 获取范围为系统的分组个数 @19
- 获取范围为产品的分组个数 @9
- 获取范围为项目的分组个数 @10
- 获取范围为执行的分组个数 @5
- 获取范围为用户的分组个数 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r(count($metric->fetchModules('system'))) && p() && e(19);   // 获取范围为系统的分组个数
r(count($metric->fetchModules('product'))) && p() && e(9);   // 获取范围为产品的分组个数
r(count($metric->fetchModules('project'))) && p() && e(10);  // 获取范围为项目的分组个数
r(count($metric->fetchModules('execution'))) && p() && e(5); // 获取范围为执行的分组个数
r(count($metric->fetchModules('user'))) && p() && e(4);      // 获取范围为用户的分组个数