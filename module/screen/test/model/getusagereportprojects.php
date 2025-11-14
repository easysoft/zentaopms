#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getUsageReportProjects();
timeout=0
cid=18260

- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2023', '01'  @2
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2024', '12'  @7
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2030', '12'  @7
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2020', '01'  @1
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2024', '06'  @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目A,项目B,项目C,测试项目D,示例项目E,示例项目F,示例项目G,示例项目H{3}');
$table->type->range('project{10}');
$table->openedDate->range('`2020-01-01`,`2023-01-15`,`2023-12-31`,`2024-06-15`,`2024-06-16`,`2024-06-17`,`2024-06-18`,`2024-12-31`,`2024-12-30`,`2024-12-29`');
$table->closedDate->range('`0000-00-00`{5},`2024-06-10`,`0000-00-00`,`0000-00-00`,`2024-12-31`,`2025-01-01`');
$table->deleted->range('0{8},1{2}');
$table->vision->range('rnd{10}');
$table->gen(10);

su('admin');

$screenTest = new screenTest();

r($screenTest->getUsageReportProjectsTest('2023', '01')) && p() && e('2');
r($screenTest->getUsageReportProjectsTest('2024', '12')) && p() && e('7');
r($screenTest->getUsageReportProjectsTest('2030', '12')) && p() && e('7');
r($screenTest->getUsageReportProjectsTest('2020', '01')) && p() && e('1');
r($screenTest->getUsageReportProjectsTest('2024', '06')) && p() && e('6');