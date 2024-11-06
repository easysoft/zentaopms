#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=测试用例详情
timeout=0
cid=1

- 测试用例详情验证
 - 测试结果 @测试用例详情页验证成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/testcase.ui.class.php';
zenData('product')->loadYaml('product')->gen(1);
zenData('case')->loadYaml('case')->gen(1);
$tester = new testcase();

$product  = array(
    'productID' => 1,
);

r($tester->testcaseView($product)) && p('message,status') && e('测试用例详情页验证成功,SUCCESS'); //测试用例详情验证