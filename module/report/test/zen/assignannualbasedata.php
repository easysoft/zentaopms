#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualBaseData();
timeout=0
cid=0

- 测试带有account参数的正常情况,返回year属性4 @2024
- 测试带有dept参数的正常情况,返回dept属性3 @1
- 测试带有year参数的正常情况,返回year属性4 @2023
- 测试空year参数的情况,应返回当前年份属性4 @2025
- 测试有效account的情况,返回dept属性3 @1
- 测试dept为0的边界情况,返回dept属性3 @0
- 测试返回的userCount元素属性1 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(20);
zenData('dept')->gen(10);
zenData('action')->gen(100);

su('admin');

$reportTest = new reportZenTest();

r($reportTest->assignAnnualBaseDataTest('admin', '1', '2024')) && p('4') && e('2024'); // 测试带有account参数的正常情况,返回year
r($reportTest->assignAnnualBaseDataTest('', '1', '2024')) && p('3') && e('1'); // 测试带有dept参数的正常情况,返回dept
r($reportTest->assignAnnualBaseDataTest('', '1', '2023')) && p('4') && e('2023'); // 测试带有year参数的正常情况,返回year
r($reportTest->assignAnnualBaseDataTest('', '1', '')) && p('4') && e('2025'); // 测试空year参数的情况,应返回当前年份
r($reportTest->assignAnnualBaseDataTest('user1', '1', '2024')) && p('3') && e('1'); // 测试有效account的情况,返回dept
r($reportTest->assignAnnualBaseDataTest('', '0', '2024')) && p('3') && e('0'); // 测试dept为0的边界情况,返回dept
r($reportTest->assignAnnualBaseDataTest('admin', '1', '2024')) && p('1') && e('10'); // 测试返回的userCount元素