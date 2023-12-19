#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

/**

title=getMetricRecordDateField
timeout=0
cid=1

*/

$metricList = array();
$metricList[0] = 'count_of_bug';
$metricList[1] = 'count_of_annual_created_product';
$metricList[2] = 'count_of_monthly_created_project';
$metricList[3] = 'count_of_weekly_created_release';
$metricList[4] = 'count_of_case_in_product';
$metricList[5] = 'count_of_annual_fixed_bug_in_product';
$metricList[6] = 'count_of_monthly_created_bug_in_product';
$metricList[7] = 'count_of_daily_closed_bug_in_product';

r($metric->getMetricRecordDateField($metricList[0])) && p('')      && e('0');              // 测试度量项count_of_bug的日期类型
r($metric->getMetricRecordDateField($metricList[1])) && p('0')     && e('year');           // 测试度量项count_of_annual_created_product的日期类型
r($metric->getMetricRecordDateField($metricList[2])) && p('0,1')   && e('year,month');     // 测试度量项count_of_monthly_created_project的日期类型
r($metric->getMetricRecordDateField($metricList[3])) && p('0,1')   && e('year,week');      // 测试度量项count_of_weekly_created_release的日期类型
r($metric->getMetricRecordDateField($metricList[4])) && p('')      && e('0');              // 测试度量项count_of_case_in_product的日期类型
r($metric->getMetricRecordDateField($metricList[5])) && p('0')     && e('year');           // 测试度量项count_of_annual_fixed_bug_in_product的日期类型
r($metric->getMetricRecordDateField($metricList[6])) && p('0,1')   && e('year,month');     // 测试度量项count_of_monthly_created_bug_in_product的日期类型
r($metric->getMetricRecordDateField($metricList[7])) && p('0,1,2') && e('year,month,day'); // 测试度量项count_of_daily_closed_bug_in_product的日期类型
