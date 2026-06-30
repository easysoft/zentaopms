#!/usr/bin/env php
<?php

/**

title=测试 searchTao::replaceDynamic();
timeout=0
cid=18343

- 测试替换 $lastWeek @1
- 测试替换 $thisWeek @1
- 测试替换 $lastMonth @1
- 测试替换 $thisMonth @1
- 测试替换 $yesterday @1
- 测试替换 $today @1
- 测试替换me @`account = 'admin'`
- 测试不包含$变量的查询 @title like 'normal query'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');
$app->loadClass('date');

// 准备测试数据
$queryList = array();
$queryList[] = "date between '\$lastWeek'";
$queryList[] = "date between '\$thisWeek'";
$queryList[] = "date between '\$lastMonth'";
$queryList[] = "date between '\$thisMonth'";
$queryList[] = "date between '\$yesterday'";
$queryList[] = "date between '\$today'";
$queryList[] = "account = '\$@me'";
$queryList[] = "title like 'normal query'";

$lastWeek  = date::getLastWeek();
$thisWeek  = date::getThisWeek();
$lastMonth = date::getLastMonth();
$thisMonth = date::getThisMonth();
$yesterday = date::yesterday();
$today     = date(DT_DATE1);

$expectedList = array();
$expectedList[] = "date between '{$lastWeek['begin']}' and '{$lastWeek['end']}'";
$expectedList[] = "date between '{$thisWeek['begin']}' and '{$thisWeek['end']}'";
$expectedList[] = "date between '{$lastMonth['begin']}' and '{$lastMonth['end']}'";
$expectedList[] = "date between '{$thisMonth['begin']}' and '{$thisMonth['end']}'";
$expectedList[] = "date between '{$yesterday} 00:00:00' and '{$yesterday} 23:59:59'";
$expectedList[] = "date between '{$today} 00:00:00' and '{$today} 23:59:59'";
$expectedList[] = "account = 'admin'";
$expectedList[] = "title like 'normal query'";

// 创建测试实例
$search = new searchTaoTest();

// 执行测试步骤
r($search->replaceDynamicTest($queryList[0]) === $expectedList[0]) && p() && e('1'); // 测试替换 $lastWeek
r($search->replaceDynamicTest($queryList[1]) === $expectedList[1]) && p() && e('1'); // 测试替换 $thisWeek
r($search->replaceDynamicTest($queryList[2]) === $expectedList[2]) && p() && e('1'); // 测试替换 $lastMonth
r($search->replaceDynamicTest($queryList[3]) === $expectedList[3]) && p() && e('1'); // 测试替换 $thisMonth
r($search->replaceDynamicTest($queryList[4]) === $expectedList[4]) && p() && e('1'); // 测试替换 $yesterday
r($search->replaceDynamicTest($queryList[5]) === $expectedList[5]) && p() && e('1'); // 测试替换 $today
r($search->replaceDynamicTest($queryList[6]) === $expectedList[6]) && p() && e('1'); // 测试替换me
r($search->replaceDynamicTest($queryList[7]) === $expectedList[7]) && p() && e('1'); // 测试不包含$变量的查询
