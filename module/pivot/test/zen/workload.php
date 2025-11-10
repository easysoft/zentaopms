#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::workload();
timeout=0
cid=0

- 执行pivotTest模块的workloadTest方法，参数是'', '', 0, 0, 0, 'assign'
 - 属性assign @assign
 - 属性workhour @7.0
- 执行pivotTest模块的workloadTest方法，参数是'2025-10-01', '2025-10-31', 0, 0, 0, 'assign'
 - 属性begin @2025-10-01
 - 属性end @2025-10-31
- 执行pivotTest模块的workloadTest方法，参数是'2025-11-01', '2025-11-15', 10, 8, 0, 'assign'
 - 属性days @10
 - 属性workhour @8
- 执行pivotTest模块的workloadTest方法，参数是'', '', 0, 0, 1, 'assign'
 - 属性dept @1
 - 属性assign @assign
- 执行pivotTest模块的workloadTest方法，参数是'', '', 0, 0, 0, 'finishedBy' 属性assign @finishedBy

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('user')->gen(10);
zenData('dept')->gen(5);
zenData('task')->gen(20);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->workloadTest('', '', 0, 0, 0, 'assign')) && p('assign,workhour') && e('assign,7.0');
r($pivotTest->workloadTest('2025-10-01', '2025-10-31', 0, 0, 0, 'assign')) && p('begin,end') && e('2025-10-01,2025-10-31');
r($pivotTest->workloadTest('2025-11-01', '2025-11-15', 10, 8, 0, 'assign')) && p('days,workhour') && e('10,8');
r($pivotTest->workloadTest('', '', 0, 0, 1, 'assign')) && p('dept,assign') && e('1,assign');
r($pivotTest->workloadTest('', '', 0, 0, 0, 'finishedBy')) && p('assign') && e('finishedBy');