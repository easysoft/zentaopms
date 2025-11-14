#!/usr/bin/env php
<?php

/**

title=测试 holidayModel::getYearPairs();
cid=16746

- 测试正常情况下获取年份对数组 >> 期望返回非空数组
- 测试特定年份查询功能 >> 期望返回指定年份
- 测试空数据库的处理 >> 期望返回空数组
- 测试添加多年份数据后的返回结果 >> 期望返回多个年份
- 测试年份降序排列功能 >> 期望最新年份在前

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/holiday.unittest.class.php';

global $tester;

zenData('holiday')->gen(5);
zenData('user')->gen(1);

su('admin');

$holidayTest = new holidayTest();

r($holidayTest->getYearPairsTest()) && p() && e('1');
r($holidayTest->getYearPairsTestWithSpecificYear('2025')) && p() && e('2025');
r($holidayTest->getYearPairsTestEmptyTable()) && p() && e('0');
r($holidayTest->getYearPairsTestMultiYear()) && p() && e('2');
r($holidayTest->getYearPairsTestOrderValidation()) && p() && e('2025');