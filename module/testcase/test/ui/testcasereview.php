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
include '../lib/testcase.ui.class.php';
zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);
/* 修改zt_config表中控制用例评审的开关。value值为'1'则开启，'0'则关闭。 */
zenData('setting')->dao->update(TABLE_CONFIG)->set('value')->eq('1')->where('module')->eq('testcase')->andWhere('`key`')->eq('needReview')->exec();
zenData('case')->loadYaml('case')->gen(1);

$tester = new testcase();

$product  = array(
    'productID' => 1,
);
$testcase = array(
    'reviewedDate' => '2025-09-09',
    'result'       => '确认通过',
    'reviewedBy'   => array('admin', '研发'),
    'comment'      => '备注一下'
);

r($tester->testcaseReview($product, $testcase)) && p('message,status') && e('测试用例评审通过,SUCCESS'); //测试用例评审通过。