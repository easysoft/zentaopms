#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualReport();
timeout=0
cid=0

- 执行reportTest模块的assignAnnualReportTest方法，参数是'2024', '1', '' 
 - 属性success @yes
 - 属性monthsCount @12
 - 属性yearValid @yes
- 执行reportTest模块的assignAnnualReportTest方法，参数是'', '1', '' 
 - 属性success @yes
 - 属性yearValid @yes
 - 属性deptValid @yes
- 执行reportTest模块的assignAnnualReportTest方法，参数是'2024', '1', 'admin' 
 - 属性success @yes
 - 属性accountValid @yes
 - 属性deptValid @yes
- 执行reportTest模块的assignAnnualReportTest方法，参数是'2024', '0', '' 
 - 属性success @yes
 - 属性deptValid @yes
 - 属性accountValid @yes
- 执行reportTest模块的assignAnnualReportTest方法，参数是'2023', '999', 'nonexistent' 
 - 属性success @yes
 - 属性yearValid @yes
 - 属性accountValid @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 准备最简测试数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->dept->range('1{2},2{2},3{1}');
$user->gen(5);

$dept = zenData('dept');
$dept->id->range('1-3');
$dept->name->range('开发部,测试部,产品部');
$dept->parent->range('0');
$dept->gen(3);

su('admin');

$reportTest = new reportTest();

r($reportTest->assignAnnualReportTest('2024', '1', '')) && p('success,monthsCount,yearValid') && e('yes,12,yes');
r($reportTest->assignAnnualReportTest('', '1', '')) && p('success,yearValid,deptValid') && e('yes,yes,yes');
r($reportTest->assignAnnualReportTest('2024', '1', 'admin')) && p('success,accountValid,deptValid') && e('yes,yes,yes');
r($reportTest->assignAnnualReportTest('2024', '0', '')) && p('success,deptValid,accountValid') && e('yes,yes,yes');
r($reportTest->assignAnnualReportTest('2023', '999', 'nonexistent')) && p('success,yearValid,accountValid') && e('yes,yes,yes');