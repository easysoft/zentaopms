#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getProductStats();
timeout=0
cid=1

- 测试是否能拿到数据第1条的code属性 @test
- 测试是否能拿到数据第1[plans]条的1属性 @Test plan

*/

$tutorial = new tutorialTest();

r($tutorial->getProductStatsTest()) && p('1:code')     && e('test');      //测试是否能拿到数据
r($tutorial->getProductStatsTest()) && p('1[plans]:1') && e('Test plan'); //测试是否能拿到数据