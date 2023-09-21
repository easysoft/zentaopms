#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('product')->gen('45');
zdTable('branch')->gen('10');
zdTable('module')->config('casemodule')->gen('50');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getDatatableModules();
cid=1
pid=1

*/

$productIDList = array(1, 2, 3, 4, 5, 41);

$testcase = new testcaseTest();
r($testcase->getDatatableModulesTest($productIDList[0])) && p() && e('0,1,4');   // 获取产品 1  的模块
r($testcase->getDatatableModulesTest($productIDList[1])) && p() && e('0,5,8');   // 获取产品 2  的模块
r($testcase->getDatatableModulesTest($productIDList[2])) && p() && e('0,12,9');  // 获取产品 3  的模块
r($testcase->getDatatableModulesTest($productIDList[3])) && p() && e('0,13,16'); // 获取产品 4  的模块
r($testcase->getDatatableModulesTest($productIDList[4])) && p() && e('0,17,20'); // 获取产品 5  的模块
r($testcase->getDatatableModulesTest($productIDList[5])) && p() && e('0,41,44'); // 获取产品 41 的模块
