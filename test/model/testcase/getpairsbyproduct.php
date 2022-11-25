#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getPairsByProduct();
cid=1
pid=1

获取产品1的case       >> 1:这个是测试用例1
获取产品41分支1的case >> empty

*/
$productIDList = array(1, 41);
$branch        = 1;

$testcase = new testcaseTest();
r($testcase->getPairsByProductTest($productIDList[0]))          && p('1') && e('1:这个是测试用例1'); // 获取产品1的case
r($testcase->getPairsByProductTest($productIDList[1], $branch)) && p()    && e('empty'); // 获取产品41分支1的case
