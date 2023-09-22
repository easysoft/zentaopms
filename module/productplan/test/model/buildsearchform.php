#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
su('admin');

/**

title=测试planModel->buildSearchForm();
cid=1
pid=1

*/

$queryIDList   = array(0, 1);
$productIDList = array(1, 6);

$plan = new productPlan();
r($plan->buildsearchformtest($queryIDList[1], $productIDList[0])) && p() && e('1'); // 正确的queryid
r($plan->buildsearchformtest($queryIDList[0], $productIDList[0])) && p() && e('0'); // 错误的queryid

r($plan->buildsearchformtest($queryIDList[1], $productIDList[1])) && p() && e('1'); // 正确的queryid
r($plan->buildsearchformtest($queryIDList[0], $productIDList[1])) && p() && e('0'); // 错误的queryid
