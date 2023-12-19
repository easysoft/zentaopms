#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

/**

title=getViewTableHeader
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

r($metric->getViewTableHeader($metricList[0])) && p('0:name,title;1:name,title')                           && e('value,数值,calcTime,采集时间');                          // 测试度量项count_of_bug
r($metric->getViewTableHeader($metricList[1])) && p('0:name,title;1:name,title;2:name,title')              && e('date,日期,value,数值,calcTime,采集时间');                // 测试度量项count_of_annual_created_product
r($metric->getViewTableHeader($metricList[2])) && p('0:name,title;1:name,title;2:name,title')              && e('date,日期,value,数值,calcTime,采集时间');                // 测试度量项count_of_monthly_created_project
r($metric->getViewTableHeader($metricList[3])) && p('0:name,title;1:name,title;2:name,title')              && e('date,日期,value,数值,calcTime,采集时间');                // 测试度量项count_of_weekly_created_release
r($metric->getViewTableHeader($metricList[4])) && p('0:name,title;1:name,title;2:name,title')              && e('scope,产品名称,value,数值,calcTime,采集时间');           // 测试度量项count_of_case_in_product
r($metric->getViewTableHeader($metricList[5])) && p('0:name,title;1:name,title;2:name,title;3:name,title') && e('scope,产品名称,date,日期,value,数值,calcTime,采集时间'); // 测试度量项count_of_annual_fixed_bug_in_product
r($metric->getViewTableHeader($metricList[6])) && p('0:name,title;1:name,title;2:name,title;3:name,title') && e('scope,产品名称,date,日期,value,数值,calcTime,采集时间'); // 测试度量项count_of_monthly_created_bug_in_product
r($metric->getViewTableHeader($metricList[7])) && p('0:name,title;1:name,title;2:name,title;3:name,title') && e('scope,产品名称,date,日期,value,数值,calcTime,采集时间'); // 测试度量项count_of_daily_closed_bug_in_product
