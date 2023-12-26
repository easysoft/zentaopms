#!/usr/bin/env php
<?php

/**

title=getResultByCode
timeout=0
cid=1

- 测试度量项count_of_bug第0条的value属性 @24
- 测试度量项count_of_annual_created_product
 - 第0条的year属性 @2023
 - 第0条的value属性 @5
- 测试度量项count_of_monthly_created_project
 - 第0条的year属性 @2023
 - 第0条的month属性 @09
- 测试度量项count_of_weekly_created_release
 - 第0条的year属性 @2010
 - 第0条的week属性 @12
 - 第0条的value属性 @1
- 测试度量项count_of_case_in_product
 - 第0条的product属性 @1
 - 第0条的value属性 @40
- 测试度量项count_of_annual_fixed_bug_in_product
 - 第0条的year属性 @2012
 - 第0条的product属性 @1
 - 第0条的value属性 @3
- 测试度量项count_of_monthly_created_bug_in_product
 - 第0条的product属性 @1
 - 第0条的year属性 @2010
 - 第0条的month属性 @01
 - 第0条的value属性 @4
 - 第1条的product属性 @1
 - 第1条的year属性 @2010
 - 第1条的month属性 @02
 - 第1条的value属性 @2
- 测试度量项count_of_daily_closed_bug_in_product
 - 第0条的product属性 @1
 - 第0条的year属性 @2012
 - 第0条的month属性 @01
 - 第0条的day属性 @21
 - 第0条的value属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');
$metric = new metricTest();

zdTable('metriclib')->config('metriclib_system_product', true)->gen(80);
zdTable('bug')->config('bug_resolution_status', true)->gen(48);
zdTable('product')->config('product', true)->gen(10);
zdTable('project')->config('project', true)->gen(80);
zdTable('release')->config('release', true)->gen(20);
zdTable('case')->config('case', true)->gen(80);

$codeList = array();
$codeList[0] = 'count_of_bug';
$codeList[1] = 'count_of_annual_created_product';
$codeList[2] = 'count_of_monthly_created_project';
$codeList[3] = 'count_of_weekly_created_release';
$codeList[4] = 'count_of_case_in_product';
$codeList[5] = 'count_of_annual_fixed_bug_in_product';
$codeList[6] = 'count_of_monthly_created_bug_in_product';
$codeList[7] = 'count_of_daily_closed_bug_in_product';

r($metric->getResultByCode($codeList[0])) && p('0:value')                                               && e('24');                      // 测试度量项count_of_bug
r($metric->getResultByCode($codeList[1])) && p('0:year,value')                                          && e('2023,5');                  // 测试度量项count_of_annual_created_product
r($metric->getResultByCode($codeList[2])) && p('0:year,month')                                          && e('2023,09');                 // 测试度量项count_of_monthly_created_project
r($metric->getResultByCode($codeList[3])) && p('0:year,week,value')                                     && e('2010,12,1');               // 测试度量项count_of_weekly_created_release
r($metric->getResultByCode($codeList[4])) && p('0:product,value')                                       && e('1,40');                    // 测试度量项count_of_case_in_product
r($metric->getResultByCode($codeList[5])) && p('0:year,product,value')                                  && e('2012,1,3');                // 测试度量项count_of_annual_fixed_bug_in_product
r($metric->getResultByCode($codeList[6])) && p('0:product,year,month,value;1:product,year,month,value') && e('1,2010,01,4,1,2010,02,2'); // 测试度量项count_of_monthly_created_bug_in_product
r($metric->getResultByCode($codeList[7])) && p('0:product,year,month,day,value')                        && e('1,2012,01,21,1');          // 测试度量项count_of_daily_closed_bug_in_product