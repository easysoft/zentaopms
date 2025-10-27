#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=测试用例评审
timeout=0
cid=1

- 测试用例评审通过。
 - 测试结果 @测试用例评审通过
 - 最终测试状态 @SUCCESS

*/
include '../lib/ui/testcase.ui.class.php';
zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);

zenData('case')->loadYaml('case')->gen(1);

$tester = new testcase();

$config = array(
    'module' => 'testcase',
    'field'  => 'review'
);
$product  = array(
    'productID' => 1,
);
$testcase = array(
    'reviewedDate' => '2025-09-09',
    'result'       => '确认通过',
    'reviewedBy'   => array('admin'),
    'comment'      => '备注一下'
);

r($tester->testcaseReview($config, $product, $testcase)) && p('message,status') && e('测试用例评审通过,SUCCESS'); //测试用例评审通过。
$tester->closeBrowser();