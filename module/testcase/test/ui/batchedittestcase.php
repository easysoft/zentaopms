#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=批量编辑测试用例
timeout=0
cid=1

- 验证批量编辑测试用例
 - 测试结果 @批量编辑测试用例成功
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
    'productID' => 1
);

$testcase = array(
    'case1' => array(
        'status'  => '正常',
        'title'   => 'case1-' . time(),
        'type'    => '功能测试',
        'steps'   => 'step1',
        'expects' => 'expects1',
        'stage'   => array('冒烟测试阶段', '集成测试阶段', '单元测试阶段')
    ),
    'case2' => array(
        'status'  => '待评审',
        'title'   => 'case2-' . time(),
        'type'    => '接口测试',
        'steps'   => 'step2',
        'expects' => 'expects2',
        'stage'   => array('冒烟测试阶段', '集成测试阶段', '单元测试阶段')
    ),
    'case3' => array(
        'status'  => '被阻塞',
        'title'   => 'case3-' . time(),
        'type'    => '接口测试',
        'steps'   => 'step3',
        'expects' => 'expects3',
        'stage'   => array('冒烟测试阶段', '集成测试阶段', '单元测试阶段')
    ),
);
r($tester->batchEditTestcase($url, $testcase)) && p('message,status') && e('批量编辑测试用例成功,SUCCESS'); //验证批量编辑测试用例
$tester->closeBrowser();