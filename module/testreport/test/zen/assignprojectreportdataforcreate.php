#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignProjectReportDataForCreate();
timeout=0
cid=19127

- 执行testreportTest模块的assignProjectReportDataForCreateTest方法，参数是0, 'project', '', '', '', 1 属性begin @2024-01-01
- 执行testreportTest模块的assignProjectReportDataForCreateTest方法，参数是1, 'project', 'task123', '', '', 1 属性extra @task123
- 执行testreportTest模块的assignProjectReportDataForCreateTest方法，参数是0, 'project', '', '2024-02-01', '2024-02-28', 1 属性begin @2024-02-01
- 执行testreportTest模块的assignProjectReportDataForCreateTest方法，参数是0, 'execution', '', '', '', 2 第execution条的name属性 @测试执行 - Execution
- 执行testreportTest模块的assignProjectReportDataForCreateTest方法，参数是5, 'project', '', '', '', 0 属性objectID @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

zenData('testtask');
zenData('execution');
zenData('project');
zenData('product');
zenData('build');
zenData('story');
zenData('bug');

su('admin');

$testreportTest = new testreportTest();

r($testreportTest->assignProjectReportDataForCreateTest(0, 'project', '', '', '', 1)) && p('begin') && e('2024-01-01');
r($testreportTest->assignProjectReportDataForCreateTest(1, 'project', 'task123', '', '', 1)) && p('extra') && e('task123');
r($testreportTest->assignProjectReportDataForCreateTest(0, 'project', '', '2024-02-01', '2024-02-28', 1)) && p('begin') && e('2024-02-01');
r($testreportTest->assignProjectReportDataForCreateTest(0, 'execution', '', '', '', 2)) && p('execution:name') && e('测试执行 - Execution');
r($testreportTest->assignProjectReportDataForCreateTest(5, 'project', '', '', '', 0)) && p('objectID') && e('5');