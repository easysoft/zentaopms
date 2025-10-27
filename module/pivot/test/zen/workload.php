#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::workload();
timeout=0
cid=0

- 执行pivotTest模块的workloadTest方法 
 - 属性title @工作负载表
 - 属性currentMenu @workload
 - 属性hasUsers @1
- 执行pivotTest模块的workloadTest方法，参数是'2024-01-01', '2024-01-31', 0, 8.0, 1, 'assign' 
 - 属性dept @1
 - 属性workhour @8
 - 属性assign @assign
- 执行pivotTest模块的workloadTest方法，参数是'2024-01-01', '2024-01-08', 5, 7.5, 0, 'assign' 
 - 属性days @5
 - 属性workhour @7.5
 - 属性allHour @37.5
- 执行pivotTest模块的workloadTest方法，参数是'2024-01-01', '2024-01-15', 0, 8.0, 0, 'creator' 
 - 属性assign @creator
 - 属性hasWorkload @1
- 执行pivotTest模块的workloadTest方法，参数是'', '', 0, 0, 0, 'assign' 
 - 属性workhour @8
 - 属性sessionSet @1
 - 属性hasDepts @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zendata('user');
zendata('user')->gen(10);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->workloadTest()) && p('title,currentMenu,hasUsers') && e('工作负载表,workload,1');
r($pivotTest->workloadTest('2024-01-01', '2024-01-31', 0, 8.0, 1, 'assign')) && p('dept,workhour,assign') && e('1,8,assign');
r($pivotTest->workloadTest('2024-01-01', '2024-01-08', 5, 7.5, 0, 'assign')) && p('days,workhour,allHour') && e('5,7.5,37.5');
r($pivotTest->workloadTest('2024-01-01', '2024-01-15', 0, 8.0, 0, 'creator')) && p('assign,hasWorkload') && e('creator,1');
r($pivotTest->workloadTest('', '', 0, 0, 0, 'assign')) && p('workhour,sessionSet,hasDepts') && e('8,1,1');