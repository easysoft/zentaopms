#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getProduct();
timeout=0
cid=1

- 测试是否能拿到数据属性id @1
- 测试是否能拿到数据第plans条的1属性 @Test plan

*/

$tutorial = new tutorialTest();

r($tutorial->getProductTest()) && p('id')      && e('1');         //测试是否能拿到数据
r($tutorial->getProductTest()) && p('plans:1') && e('Test plan'); //测试是否能拿到数据