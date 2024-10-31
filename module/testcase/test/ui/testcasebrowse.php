#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=测试用例列表
timeout=0
cid=1

- 测试用例列表验证成功
 - 测试结果 @测试用例列表验证成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/testcase.ui.class.php';
$data1 = zenData('product')->loadYaml('product')->gen(1);
$data2 = zenData('case')->loadYaml('case')->gen(1);
$tester = new testcase();

$project  = array(
    'productID' => 1,
);

r($tester->testcaseBrowse($project)) && p('message,status') && e('测试用例列表验证成功,SUCCESS'); //测试用例列表验证成功