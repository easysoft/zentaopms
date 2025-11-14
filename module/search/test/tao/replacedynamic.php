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
- 测试替换 $@me @account = 'admin'
- 测试不包含$变量的查询 @queryList[7]

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

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

// 创建测试实例
$search = new searchTest();

// 执行测试步骤
r($search->replaceDynamicTest($queryList[0])) && p() && e('1'); // 测试替换 $lastWeek
r($search->replaceDynamicTest($queryList[1])) && p() && e('1'); // 测试替换 $thisWeek
r($search->replaceDynamicTest($queryList[2])) && p() && e('1'); // 测试替换 $lastMonth
r($search->replaceDynamicTest($queryList[3])) && p() && e('1'); // 测试替换 $thisMonth
r($search->replaceDynamicTest($queryList[4])) && p() && e('1'); // 测试替换 $yesterday
r($search->replaceDynamicTest($queryList[5])) && p() && e('1'); // 测试替换 $today
r($search->replaceDynamicTest($queryList[6])) && p() && e("account = 'admin'"); // 测试替换 $@me
r($search->replaceDynamicTest($queryList[7])) && p() && e($queryList[7]); // 测试不包含$变量的查询