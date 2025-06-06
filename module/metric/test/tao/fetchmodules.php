#!/usr/bin/env php
<?php

/**

title=fetchModules
timeout=0
cid=1

- 获取范围为系统的分组个数 @26
- 获取范围为产品的分组个数 @9
- 获取范围为项目的分组个数 @12
- 获取范围为执行的分组个数 @10
- 获取范围为用户的分组个数 @7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r(count($metric->fetchModules('system'))) && p() && e(26);    // 获取范围为系统的分组个数
r(count($metric->fetchModules('product'))) && p() && e(9);    // 获取范围为产品的分组个数
r(count($metric->fetchModules('project'))) && p() && e(12);   // 获取范围为项目的分组个数
r(count($metric->fetchModules('execution'))) && p() && e(10); // 获取范围为执行的分组个数
r(count($metric->fetchModules('user'))) && p() && e(7);       // 获取范围为用户的分组个数
