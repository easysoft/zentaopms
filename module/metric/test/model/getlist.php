#!/usr/bin/env php
<?php

/**

title=getList
timeout=0
cid=1

- 获取系统范围内的所有度量项第0条的code属性 @count_of_pending_deployment
- 获取系统范围内未发布的度量项 @0
- 获取系统范围内发布的度量项第1条的code属性 @count_of_pending_mergeRequest
- 获取项目范围内所有的，以id_desc的度量项第0条的code属性 @count_of_finished_epic_in_project
- 获取项目范围内所有的，以id_asc的度量项第0条的code属性 @planned_period_of_project

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->getList('system', 'all')) && p('0:code') && e('count_of_pending_deployment');                   // 获取系统范围内的所有度量项
r($metric->getList('system', 'wait')) && p('') && e('0');                                                  // 获取系统范围内未发布的度量项
r($metric->getList('system', 'released')) && p('1:code') && e('count_of_pending_mergeRequest');            // 获取系统范围内发布的度量项
r($metric->getList('project', 'all', 'id_desc')) && p('0:code') && e('count_of_finished_epic_in_project'); // 获取项目范围内所有的，以id_desc的度量项
r($metric->getList('project', 'all', 'id_asc')) && p('0:code') && e('planned_period_of_project');          // 获取项目范围内所有的，以id_asc的度量项
