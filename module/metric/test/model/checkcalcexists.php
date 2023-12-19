#!/usr/bin/env php
<?php
/**
title=checkCalcExists
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$existsMetrics = array();
$existsMetrics[0] = array('code' => 'count_of_bug',                         'scope' => 'system',  'purpose' => 'scale');
$existsMetrics[1] = array('code' => 'count_of_annual_created_product',      'scope' => 'system',  'purpose' => 'scale');
$existsMetrics[2] = array('code' => 'count_of_monthly_created_project',     'scope' => 'system',  'purpose' => 'scale');
$existsMetrics[3] = array('code' => 'count_of_case_in_product',             'scope' => 'product', 'purpose' => 'scale');
$existsMetrics[4] = array('code' => 'count_of_daily_closed_bug_in_product', 'scope' => 'product', 'purpose' => 'scale');

$noExistsMetrics = array();
$noExistsMetrics[0] = array('code' => 'I"m not exists', 'scope' => 'system',  'purpose' => 'scale');
$noExistsMetrics[1] = array('code' => "count_of_bug",   'scope' => 'wrong',   'purpose' => 'scale');
$noExistsMetrics[2] = array('code' => "count_of_bug",   'scope' => 'product', 'purpose' => 'wrong');

r($metric->checkCalcExists((object)$existsMetrics[0])) && p('') && e('true'); // 测试存在的度量项 count_of_bug
r($metric->checkCalcExists((object)$existsMetrics[1])) && p('') && e('true'); // 测试存在的度量项 count_of_annual_created_product
r($metric->checkCalcExists((object)$existsMetrics[2])) && p('') && e('true'); // 测试存在的度量项 count_of_monthly_created_project
r($metric->checkCalcExists((object)$existsMetrics[3])) && p('') && e('true'); // 测试存在的度量项 count_of_case_in_product
r($metric->checkCalcExists((object)$existsMetrics[4])) && p('') && e('true'); // 测试存在的度量项 count_of_daily_closed_bug_in_product

r($metric->checkCalcExists((object)$noExistsMetrics[0])) && p('') && e('false'); // 测试错误的代号
r($metric->checkCalcExists((object)$noExistsMetrics[1])) && p('') && e('false'); // 测试错误的范围
r($metric->checkCalcExists((object)$noExistsMetrics[2])) && p('') && e('false'); // 测试错误的目的
