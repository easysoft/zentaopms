#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualReport();
timeout=0
cid=18190

- 测试带有account参数的正常情况,返回year属性year @2025
- 测试带有dept参数的正常情况,返回dept属性dept @1
- 测试带有year参数的正常情况,返回hasMonths属性hasMonths @yes
- 测试空year参数的情况,应返回当前年份属性year @2025
- 测试有效account的情况,返回hasData属性hasData @yes
- 测试dept为0的边界情况,返回dept属性dept @0
- 测试正常情况返回hasYears属性hasYears @yes
- 测试正常情况返回hasRadarData属性hasRadarData @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(20);
zenData('dept')->gen(10);
zenData('action')->gen(100);
zenData('todo')->gen(50);
zenData('effort')->gen(50);
zenData('story')->gen(30);
zenData('task')->gen(40);
zenData('bug')->gen(25);
zenData('case')->gen(30);
zenData('product')->gen(5);
zenData('project')->gen(10);

su('admin');

$reportTest = new reportZenTest();

r($reportTest->assignAnnualReportTest('2025', '1', 'admin')) && p('year') && e('2025'); // 测试带有account参数的正常情况,返回year
r($reportTest->assignAnnualReportTest('2025', '1', '')) && p('dept') && e('1'); // 测试带有dept参数的正常情况,返回dept
r($reportTest->assignAnnualReportTest('2025', '1', '')) && p('hasMonths') && e('yes'); // 测试带有year参数的正常情况,返回hasMonths
r($reportTest->assignAnnualReportTest('', '1', '')) && p('year') && e('2025'); // 测试空year参数的情况,应返回当前年份
r($reportTest->assignAnnualReportTest('2025', '1', 'admin')) && p('hasData') && e('yes'); // 测试有效account的情况,返回hasData
r($reportTest->assignAnnualReportTest('2025', '0', '')) && p('dept') && e('0'); // 测试dept为0的边界情况,返回dept
r($reportTest->assignAnnualReportTest('2025', '1', '')) && p('hasYears') && e('yes'); // 测试正常情况返回hasYears
r($reportTest->assignAnnualReportTest('2025', '1', '')) && p('hasRadarData') && e('yes'); // 测试正常情况返回hasRadarData