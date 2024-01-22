#!/usr/bin/env php
<?php

/**

title=getDateTypeByCode
timeout=0
cid=1

- 测试度量项count_of_bug的日期类型 @nodate
- 测试度量项count_of_annual_created_product的日期类型 @year
- 测试度量项count_of_monthly_created_project的日期类型 @month
- 测试度量项count_of_weekly_created_release的日期类型 @week
- 测试度量项count_of_case_in_product的日期类型 @nodate
- 测试度量项count_of_annual_fixed_bug_in_product的日期类型 @year
- 测试度量项count_of_monthly_created_bug_in_product的日期类型 @month
- 测试度量项count_of_daily_closed_bug_in_product的日期类型 @day

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');
zdTable('metriclib')->config('metriclib_system_product', true)->gen(80);

$metric = new metricTest();

$codeList = array();
$codeList[0] = 'count_of_bug';
$codeList[1] = 'count_of_annual_created_product';
$codeList[2] = 'count_of_monthly_created_project';
$codeList[3] = 'count_of_weekly_created_release';
$codeList[4] = 'count_of_case_in_product';
$codeList[5] = 'count_of_annual_fixed_bug_in_product';
$codeList[6] = 'count_of_monthly_created_bug_in_product';
$codeList[7] = 'count_of_daily_closed_bug_in_product';

r($metric->getDateTypeByCode($codeList[0])) && p('') && e('nodate'); // 测试度量项count_of_bug的日期类型
r($metric->getDateTypeByCode($codeList[1])) && p('') && e('year');   // 测试度量项count_of_annual_created_product的日期类型
r($metric->getDateTypeByCode($codeList[2])) && p('') && e('month');  // 测试度量项count_of_monthly_created_project的日期类型
r($metric->getDateTypeByCode($codeList[3])) && p('') && e('week');   // 测试度量项count_of_weekly_created_release的日期类型
r($metric->getDateTypeByCode($codeList[4])) && p('') && e('nodate'); // 测试度量项count_of_case_in_product的日期类型
r($metric->getDateTypeByCode($codeList[5])) && p('') && e('year');   // 测试度量项count_of_annual_fixed_bug_in_product的日期类型
r($metric->getDateTypeByCode($codeList[6])) && p('') && e('month');  // 测试度量项count_of_monthly_created_bug_in_product的日期类型
r($metric->getDateTypeByCode($codeList[7])) && p('') && e('day');    // 测试度量项count_of_daily_closed_bug_in_product的日期类型