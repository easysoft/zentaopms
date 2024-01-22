#!/usr/bin/env php
<?php

/**

title=deduplication
timeout=0
cid=1

- 测试去重后count_of_bug的数据条数 @2
- 测试去重后count_of_case_in_product的数据条数 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');
zdTable('metriclib')->config('metriclib_deduplication', true)->gen(160);

$metric = new metricTest();

$codeList = array();
$codeList[] = 'count_of_bug';
$codeList[] = 'count_of_case_in_product';

r($metric->deduplication($codeList[0])) && p('') && e('2'); // 测试去重后count_of_bug的数据条数
r($metric->deduplication($codeList[1])) && p('') && e('4'); // 测试去重后count_of_case_in_product的数据条数