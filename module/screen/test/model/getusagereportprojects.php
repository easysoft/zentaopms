#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getUsageReportProjects();
timeout=0
cid=0

- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2025', '07'  @20
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2025', '12'  @20
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2020', '01'  @0
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2024', '06'  @0
- 执行screenTest模块的getUsageReportProjectsTest方法，参数是'2030', '06'  @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(20);

su('admin');

$screenTest = new screenTest();

r(count($screenTest->getUsageReportProjectsTest('2025', '07'))) && p('') && e(20);
r(count($screenTest->getUsageReportProjectsTest('2025', '12'))) && p('') && e(20);
r(count($screenTest->getUsageReportProjectsTest('2020', '01'))) && p('') && e(0);
r(count($screenTest->getUsageReportProjectsTest('2024', '06'))) && p('') && e(0);
r(count($screenTest->getUsageReportProjectsTest('2030', '06'))) && p('') && e(20);