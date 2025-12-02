#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::buildReportDataForView();
timeout=0
cid=0

- 步骤1:完整报告数据的begin和end属性验证
 - 属性begin @2024-01-01
 - 属性end @2024-01-31
- 步骤2:完整报告数据的execution.id属性验证第execution条的id属性 @1
- 步骤3:不同日期范围的报告数据begin和end属性验证
 - 属性begin @2024-02-01
 - 属性end @2024-02-28
- 步骤4:不同execution的报告数据execution.id属性验证第execution条的id属性 @4
- 步骤5:不同product的报告数据execution.id属性验证第execution条的id属性 @3
- 步骤6:完整报告数据的report.title属性验证第report条的title属性 @测试报告1
- 步骤7:完整报告数据的report.id属性验证第report条的id属性 @1
- 步骤8:不同报告的report.id属性验证第report条的id属性 @4

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
// buildReportDataForView 方法主要进行数据转换,不需要复杂的数据库准备

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testreportTest = new testreportTest();

// 5. 构建测试报告对象
$report1 = new stdClass();
$report1->id = 1;
$report1->title = '测试报告1';
$report1->begin = '2024-01-01';
$report1->end = '2024-01-31';
$report1->product = 1;
$report1->execution = 1;
$report1->tasks = '1,2,3';
$report1->builds = '1,2';
$report1->stories = '1,2,3';
$report1->bugs = '1,2';
$report1->cases = '1,2,3,4,5';

$report2 = new stdClass();
$report2->id = 2;
$report2->title = '测试报告2';
$report2->begin = '2024-02-01';
$report2->end = '2024-02-28';
$report2->product = 1;
$report2->execution = 2;
$report2->tasks = '4,5';
$report2->builds = '3';
$report2->stories = '4,5';
$report2->bugs = '3';
$report2->cases = '6,7,8';

$report3 = new stdClass();
$report3->id = 3;
$report3->title = '测试报告3';
$report3->begin = '2024-03-01';
$report3->end = '2024-03-31';
$report3->product = 2;
$report3->execution = 3;
$report3->tasks = '';
$report3->builds = '';
$report3->stories = '';
$report3->bugs = '';
$report3->cases = '';

$report4 = new stdClass();
$report4->id = 4;
$report4->title = '测试报告4';
$report4->begin = '2024-04-01';
$report4->end = '2024-04-30';
$report4->product = 3;
$report4->execution = 4;
$report4->tasks = '6,7,8';
$report4->builds = '4,5,6';
$report4->stories = '6,7,8,9';
$report4->bugs = '4,5';
$report4->cases = '9,10,11,12';

// 6. 强制要求:必须包含至少5个测试步骤
r($testreportTest->buildReportDataForViewTest($report1)) && p('begin,end') && e('2024-01-01,2024-01-31'); // 步骤1:完整报告数据的begin和end属性验证
r($testreportTest->buildReportDataForViewTest($report1)) && p('execution:id') && e('1'); // 步骤2:完整报告数据的execution.id属性验证
r($testreportTest->buildReportDataForViewTest($report2)) && p('begin,end') && e('2024-02-01,2024-02-28'); // 步骤3:不同日期范围的报告数据begin和end属性验证
r($testreportTest->buildReportDataForViewTest($report4)) && p('execution:id') && e('4'); // 步骤4:不同execution的报告数据execution.id属性验证
r($testreportTest->buildReportDataForViewTest($report3)) && p('execution:id') && e('3'); // 步骤5:不同product的报告数据execution.id属性验证
r($testreportTest->buildReportDataForViewTest($report1)) && p('report:title') && e('测试报告1'); // 步骤6:完整报告数据的report.title属性验证
r($testreportTest->buildReportDataForViewTest($report1)) && p('report:id') && e('1'); // 步骤7:完整报告数据的report.id属性验证
r($testreportTest->buildReportDataForViewTest($report4)) && p('report:id') && e('4'); // 步骤8:不同报告的report.id属性验证