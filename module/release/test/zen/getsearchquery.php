#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::getSearchQuery();
timeout=0
cid=0

- 执行releaseTest模块的getSearchQueryTest方法  @ 1 = 1
- 执行releaseTest模块的getSearchQueryTest方法，参数是1  @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 执行releaseTest模块的getSearchQueryTest方法  @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 执行releaseTest模块的getSearchQueryTest方法，参数是999  @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 执行releaseTest模块的getSearchQueryTest方法，参数是2  @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

zenData('userquery')->loadYaml('getsearchquery/userquery', false, 2)->gen(10);

su('admin');

global $tester;
$release = $tester->loadModel('release');

$releaseTest = new releaseZenTest();

r($releaseTest->getSearchQueryTest(0)) && p() && e(' 1 = 1');
r($releaseTest->getSearchQueryTest(1)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");
r($releaseTest->getSearchQueryTest(0)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");
r($releaseTest->getSearchQueryTest(999)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");
r($releaseTest->getSearchQueryTest(2)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'");