#!/usr/bin/env php
<?php
/**
title=getResultByCodes
timeout=0
cid=1
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
$codeList[0] = array('count_of_bug', 'count_of_annual_created_product');
$codeList[1] = array('count_of_monthly_created_project', 'count_of_weekly_created_release');
$codeList[2] = array('count_of_case_in_product',  'count_of_annual_fixed_bug_in_product', 'count_of_monthly_created_bug_in_product');

r($metric->getResultByCodes($codeList[0], '', 'count_of_bug')) && p('0:value') && e('24');                             // 测试第一组度量项的count_of_bug
r($metric->getResultByCodes($codeList[0], '', 'count_of_annual_created_product')) && p('0:year,value') && e('2023,5'); // 测试第一组度量项的count_of_annual_created_product

r($metric->getResultByCodes($codeList[1], '', 'count_of_monthly_created_project')) && p('0:year,month,value') && e('2023,09,3'); // 测试第二粗度量项的count_of_monthly_created_project
r($metric->getResultByCodes($codeList[1], '', 'count_of_weekly_created_release')) && p('0:year,week,value') && e('2010,12,1');   // 测试第二粗度量项的count_of_weekly_created_release

r($metric->getResultByCodes($codeList[2], '', 'count_of_case_in_product')) && p('0:product,value') && e('1,40');                                  // 测试第三组度量项的count_of_case_in_product
r($metric->getResultByCodes($codeList[2], '', 'count_of_annual_fixed_bug_in_product')) && p('0:product,year,value') && e('1,2012,3');             // 测试第三组度量项的count_of_annual_fixed_bug_in_product
r($metric->getResultByCodes($codeList[2], '', 'count_of_monthly_created_bug_in_product')) && p('0:product,year,month,value') && e('1,2010,01,4'); // 测试第三组度量项的count_of_monthly_created_bug_in_product
