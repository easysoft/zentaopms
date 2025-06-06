#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

/**

title=测试 searchModel->setSearchParams();
timeout=0
cid=1

- 测试替换 $lastWeek @1
- 测试替换 $thisWeek @1
- 测试替换 $lastMonth @1
- 测试替换 $thisMonth @1
- 测试替换 $yesterday @1
- 测试替换 $today @1

*/

$queryList = array();
$queryList[] = "date between '\$lastWeek'";
$queryList[] = "date between '\$thisWeek'";
$queryList[] = "date between '\$lastMonth'";
$queryList[] = "date between '\$thisMonth'";
$queryList[] = "date between '\$yesterday'";
$queryList[] = "date between '\$today'";

$search = new searchTest();
r($search->replaceDynamicTest($queryList['0'])) && p() && e('1'); //测试替换 $lastWeek
r($search->replaceDynamicTest($queryList['1'])) && p() && e('1'); //测试替换 $thisWeek
r($search->replaceDynamicTest($queryList['2'])) && p() && e('1'); //测试替换 $lastMonth
r($search->replaceDynamicTest($queryList['3'])) && p() && e('1'); //测试替换 $thisMonth
r($search->replaceDynamicTest($queryList['4'])) && p() && e('1'); //测试替换 $yesterday
r($search->replaceDynamicTest($queryList['5'])) && p() && e('1'); //测试替换 $today