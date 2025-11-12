#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getJobSearchQuery();
timeout=0
cid=0

- 测试查询ID为0时，返回默认查询条件 @1 = 1
- 测试查询ID为1时，返回对应的SQL查询语句并添加t1.前缀 @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 测试查询ID为2时，返回对应的SQL查询语句并添加t1.前缀 @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 测试查询ID为3时，返回对应的SQL查询语句并添加t1.前缀 @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 测试查询ID为99（不存在）时，返回默认查询条件 @ 1 = 1
- 测试查询ID为-1（无效值）时，返回默认查询条件 @ 1 = 1
- 测试session中没有jobQuery时，自动设置默认查询条件 @ 1 = 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('userquery')->loadYaml('getjobsearchquery/userquery', false, 2)->gen(10);

su('admin');

global $tester;
$jobTest = new jobZenTest();

unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(0))  && p() && e('1 = 1');                                                       // 测试查询ID为0时，返回默认查询条件
r($jobTest->getJobSearchQueryTest(1))  && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");  // 测试查询ID为1时，返回对应的SQL查询语句并添加t1.前缀
unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(2))  && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");  // 测试查询ID为2时，返回对应的SQL查询语句并添加t1.前缀
unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(3))  && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");  // 测试查询ID为3时，返回对应的SQL查询语句并添加t1.前缀
unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(99)) && p() && e(' 1 = 1');                                                      // 测试查询ID为99（不存在）时，返回默认查询条件
unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(-1)) && p() && e(' 1 = 1');                                                      // 测试查询ID为-1（无效值）时，返回默认查询条件
unset($_SESSION['jobQuery']);
r($jobTest->getJobSearchQueryTest(0))  && p() && e(' 1 = 1');                                                      // 测试session中没有jobQuery时，自动设置默认查询条件