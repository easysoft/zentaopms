#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
su('admin');

/**

title=测试planModel->buildSearchForm();
timeout=0
cid=17622

- 正确的queryid @1
- 错误的queryid @0
- 正确的queryid @1
- 正确的queryid @1
- 错误的queryid @0

*/

$queryIDList   = array(0, 1);
$productIDList = array(1, 6);

$plan = new productPlan();
r($plan->buildsearchformtest(1, 1)) && p() && e('1'); // 正确的queryid
r($plan->buildsearchformtest(0, 1)) && p() && e('0'); // 错误的queryid
r($plan->buildsearchformtest(1, 1)) && p() && e('1'); // 正确的queryid
r($plan->buildsearchformtest(1, 6)) && p() && e('1'); // 正确的queryid
r($plan->buildsearchformtest(0, 6)) && p() && e('0'); // 错误的queryid
