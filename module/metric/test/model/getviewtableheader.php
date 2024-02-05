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

- 测试度量项count_of_bug
 - 第0条的name属性 @value
 - 第0条的title属性 @数值
 - 第1条的name属性 @calcTime
 - 第1条的title属性 @采集时间
- 测试度量项count_of_annual_created_product
 - 第0条的name属性 @date
 - 第0条的title属性 @日期
 - 第1条的name属性 @value
 - 第1条的title属性 @数值
 - 第2条的name属性 @calcTime
 - 第2条的title属性 @采集时间
- 测试度量项count_of_monthly_created_project
 - 第0条的name属性 @date
 - 第0条的title属性 @日期
 - 第1条的name属性 @value
 - 第1条的title属性 @数值
 - 第2条的name属性 @calcTime
 - 第2条的title属性 @采集时间
- 测试度量项count_of_weekly_created_release
 - 第0条的name属性 @date
 - 第0条的title属性 @日期
 - 第1条的name属性 @value
 - 第1条的title属性 @数值
 - 第2条的name属性 @calcTime
 - 第2条的title属性 @采集时间
- 测试度量项count_of_case_in_product
 - 第0条的name属性 @scope
 - 第0条的title属性 @产品名称
 - 第1条的name属性 @value
 - 第1条的title属性 @数值
 - 第2条的name属性 @calcTime
 - 第2条的title属性 @采集时间
- 测试度量项count_of_annual_fixed_bug_in_product
 - 第0条的name属性 @scope
 - 第0条的title属性 @产品名称
 - 第1条的name属性 @date
 - 第1条的title属性 @日期
 - 第2条的name属性 @value
 - 第2条的title属性 @数值
 - 第3条的name属性 @calcTime
 - 第3条的title属性 @采集时间
- 测试度量项count_of_monthly_created_bug_in_product
 - 第0条的name属性 @scope
 - 第0条的title属性 @产品名称
 - 第1条的name属性 @date
 - 第1条的title属性 @日期
 - 第2条的name属性 @value
 - 第2条的title属性 @数值
 - 第3条的name属性 @calcTime
 - 第3条的title属性 @采集时间
- 测试度量项count_of_daily_closed_bug_in_product
 - 第0条的name属性 @scope
 - 第0条的title属性 @产品名称
 - 第1条的name属性 @date
 - 第1条的title属性 @日期
 - 第2条的name属性 @value
 - 第2条的title属性 @数值
 - 第3条的name属性 @calcTime
 - 第3条的title属性 @采集时间

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