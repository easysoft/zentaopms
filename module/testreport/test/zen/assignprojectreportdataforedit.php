#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignProjectReportDataForEdit();
timeout=0
cid=19128

- 执行testreportTest模块的assignProjectReportDataForEditTest方法，参数是$completeReport
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
- 执行testreportTest模块的assignProjectReportDataForEditTest方法，参数是$completeReport, '2024-03-01' 属性begin @2024-03-01
- 执行testreportTest模块的assignProjectReportDataForEditTest方法，参数是$completeReport, '', '2024-05-31' 属性end @2024-05-31
- 执行testreportTest模块的assignProjectReportDataForEditTest方法，参数是$completeReport, '2024-04-01', '2024-04-30'
 - 属性begin @2024-04-01
 - 属性end @2024-04-30
- 执行testreportTest模块的assignProjectReportDataForEditTest方法，参数是$minimalReport
 - 属性begin @2024-02-01
 - 属性end @2024-02-28

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

su('admin');

$testreportTest = new testreportTest();

$completeReport = new stdClass();
$completeReport->id = 1;
$completeReport->title = '完整测试报告';
$completeReport->begin = '2024-01-01';
$completeReport->end = '2024-01-31';
$completeReport->product = 1;
$completeReport->execution = 1;
$completeReport->tasks = '1,2,3';
$completeReport->builds = '1,2';
$completeReport->stories = '1,2,3';
$completeReport->bugs = '1,2';

$minimalReport = new stdClass();
$minimalReport->id = 2;
$minimalReport->begin = '2024-02-01';
$minimalReport->end = '2024-02-28';
$minimalReport->product = 2;
$minimalReport->execution = 2;
$minimalReport->tasks = '4';
$minimalReport->builds = '3';

r($testreportTest->assignProjectReportDataForEditTest($completeReport)) && p('begin,end') && e('2024-01-01,2024-01-31');
r($testreportTest->assignProjectReportDataForEditTest($completeReport, '2024-03-01')) && p('begin') && e('2024-03-01');
r($testreportTest->assignProjectReportDataForEditTest($completeReport, '', '2024-05-31')) && p('end') && e('2024-05-31');
r($testreportTest->assignProjectReportDataForEditTest($completeReport, '2024-04-01', '2024-04-30')) && p('begin,end') && e('2024-04-01,2024-04-30');
r($testreportTest->assignProjectReportDataForEditTest($minimalReport)) && p('begin,end') && e('2024-02-01,2024-02-28');