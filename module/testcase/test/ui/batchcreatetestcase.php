#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=创建测试用例
timeout=0
cid=1

- 验证批量创建测试用例成功
 - 测试结果 @批量创建测试用例成功 
 - 最终测试状态 @SUCCESS

*/
include '../lib/testcase.ui.class.php';
zenData('product')->loadYaml('product')->gen(1);
$tester = new testcase();

$testcase = array(
    'caseName'       => array('testcase1' . time(), 'testcase2' . time(), 'testcase3' . time()),
    'type'           => '安装部署',
    'stage'          => array('单元测试阶段', '功能测试阶段', '集成测试阶段', '系统测试阶段', '冒烟测试阶段', '版本验证阶段'),
    'pri'            => '2',
    'precondition'   => '前置条件测试',
);

$product  = array(
    'productID' => 1,
    'branch'    => 0,
    'moduleID'  => 0
);

r($tester->batchCreate($product, $testcase)) && p('message,status') && e('批量创建测试用例成功 ,SUCCESS'); //验证批量创建测试用例成功