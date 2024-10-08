#!/usr/bin/env php
<?php

/**

title=deduplication
timeout=0
cid=1

- 测试去重后count_of_bug的数据条数 @9
- 测试去重后count_of_annual_created_project的数据条数 @5
- 测试去重后count_of_release_in_product的数据条数 @10
- 测试去重后count_of_monthly_finished_story_in_product的数据条数 @8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');
zenData('metriclib')->loadYaml('metriclib_deduplication', true)->gen(40, true, false);

$metric = new metricTest();

$codeList = array();
$codeList[0] = 'count_of_bug';
$codeList[1] = 'count_of_annual_created_project';
$codeList[2] = 'count_of_release_in_product';
$codeList[3] = 'count_of_monthly_finished_story_in_product';

r($metric->deduplication($codeList[0])) && p('') && e('9');  // 测试去重后count_of_bug的数据条数
r($metric->deduplication($codeList[1])) && p('') && e('5');  // 测试去重后count_of_annual_created_project的数据条数
r($metric->deduplication($codeList[2])) && p('') && e('10'); // 测试去重后count_of_release_in_product的数据条数
r($metric->deduplication($codeList[3])) && p('') && e('8');  // 测试去重后count_of_monthly_finished_story_in_product的数据条数