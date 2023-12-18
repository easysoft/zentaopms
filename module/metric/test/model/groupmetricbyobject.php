#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

/**

title=groupMetricByObject
cid=1
pid=1

*/

$systemMetrics = $metric->getList('system', 'all');

r($metric->groupMetricByObject($systemMetrics, true, array('group' => 'doc', 'key' => 0))) && p('code') && e('count_of_annual_created_doc'); // 获取系统度量项文档分组的第3个度量的code
r($metric->groupMetricByObject($systemMetrics, true, array('group' => 'doc', 'key' => 2))) && p('code') && e('0');                           // 获取系统度量项文档分组的第3个度量的code
r($metric->groupMetricByObject($systemMetrics, true, array('group' => 'docc', 'key' => 0))) && p('code') && e('0');                          // 获取系统度量项docc分组的第一个度量的code
r($metric->groupMetricByObject($systemMetrics, false, array('group' => 'doc'))) && p('count') && e(2);                                       // 获取系统度量项文档分组的求和
r($metric->groupMetricByObject($systemMetrics, false, array('group' => 'product'))) && p('count') && e(5);                                   // 获取系统度量项产品分组的求和
r($metric->groupMetricByObject($systemMetrics, false, array('group' => 'project'))) && p('count') && e(22);                                  // 获取系统度量项项目分组的求和
r($metric->groupMetricByObject($systemMetrics, false, array('group' => 'projectt'))) && p('count') && e(0);                                  // 获取系统度量项projectt分组的求和
