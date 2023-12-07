#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

/**

title=测试 searchModel->setSearchParams();
timeout=0
cid=1

- 测试替换 $lastWeek @date between '2023-11-20 00:00:00' and '2023-11-26 23:59:59'
- 测试替换 $thisWeek @date between '2023-11-27 00:00:00' and '2023-12-03 23:59:59'
- 测试替换 $lastMonth @date between '2023-10-01 00:00:00' and '2023-10-31 23:59:59'
- 测试替换 $thisMonth @date between '2023-11-01 00:00:00' and '2023-11-30 23:59:59'
- 测试替换 $yesterday @date between '2023-11-29 00:00:00' and '2023-11-29 23:59:59'
- 测试替换 $today @date between '2023-11-30 00:00:00' and '2023-11-30 23:59:59'

*/

$queryList = array();
$queryList[] = "date between '\$lastWeek'";
$queryList[] = "date between '\$thisWeek'";
$queryList[] = "date between '\$lastMonth'";
$queryList[] = "date between '\$thisMonth'";
$queryList[] = "date between '\$yesterday'";
$queryList[] = "date between '\$today'";

$search = new searchTest();
r($search->replaceDynamicTest($queryList['0'])) && p() && e("date between '2023-11-20 00:00:00' and '2023-11-26 23:59:59'"); //测试替换 $lastWeek
r($search->replaceDynamicTest($queryList['1'])) && p() && e("date between '2023-11-27 00:00:00' and '2023-12-03 23:59:59'"); //测试替换 $thisWeek
r($search->replaceDynamicTest($queryList['2'])) && p() && e("date between '2023-10-01 00:00:00' and '2023-10-31 23:59:59'"); //测试替换 $lastMonth
r($search->replaceDynamicTest($queryList['3'])) && p() && e("date between '2023-11-01 00:00:00' and '2023-11-30 23:59:59'"); //测试替换 $thisMonth
r($search->replaceDynamicTest($queryList['4'])) && p() && e("date between '2023-11-29 00:00:00' and '2023-11-29 23:59:59'"); //测试替换 $yesterday
r($search->replaceDynamicTest($queryList['5'])) && p() && e("date between '2023-11-30 00:00:00' and '2023-11-30 23:59:59'"); //测试替换 $today