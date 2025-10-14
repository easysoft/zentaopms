#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=导出测试用例
timeout=0
cid=1

- 验证导出测试用例
 - 测试结果 @导出测试用例成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/ui/testcase.ui.class.php';
$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-2');
$story->root->range('1-2');
$story->path->range('`,1,`, `,2,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

$caseStep = zenData('casestep');
$caseStep->id->range('1-3');
$caseStep->parent->range('0');
$caseStep->case->range('1-3');
$caseStep->version->range('1');
$caseStep->type->range('step');
$caseStep->desc->range('1-3');
$caseStep->expect->range('1-3');
$caseStep->gen(3);

$caseSpec = zenData('casespec');
$caseSpec->id->range('1-3');
$caseSpec->case->range('1-3');
$caseSpec->version->range('1');
$caseSpec->title->range('1-3');
$caseSpec->precondition->range('1-3');
$caseSpec->gen(3);

zenData('case')->loadYaml('case')->gen(3);

$tester = new testcase();

$url = array(
    'productID'  => 1
);

$testcase = array(
    'fileName'   => 'case',
    'fileType'   => 'csv',
    'encode'     => 'GBK',
    'exportType' => '全部记录'
);
r($tester->exportTestcase($url, $testcase)) && p('message,status') && e('导出测试用例成功,SUCCESS'); //验证导出测试用例
$tester->closeBrowser();