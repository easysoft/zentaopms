#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=编辑测试用例
timeout=0
cid=1

- 验证编辑测试用例
 - 测试结果 @编辑测试用例成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/testcase.ui.class.php';
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
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

zenData('case')->loadYaml('case')->gen(1);

$tester = new testcase();

$url = array(
    'caseID'       => 1,
    'comment'      => 'false',
    'excecutionID' => 0
);

$testcase = array(
    'caseName'       => 'testcase' . time(),
    'type'           => '安装部署',
    'stage'          => array('单元测试阶段', '功能测试阶段', '集成测试阶段', '系统测试阶段', '冒烟测试阶段', '版本验证阶段'),
    'pri'            => '2',
    'precondition'   => '前置条件测试',
    'steps'          => array(
        'step1'  => 'expect1',
        'step2'  => 'expect2',
        'step3'  => 'expect3',
        'step4'  => 'expect4',
        'step5'  => 'expect5',
        'step6'  => 'expect6',
        'group7' => array('step7.1' => 'expect7.1', 'step7.2' => 'expect7.2', 'step7.3' => 'expect7.3', 'step7.4' => 'expect7.4', 'step7.5' => 'expect7.5'),
        'group8' => array('step8.1' => 'expect8.1', 'step8.2' => 'expect8.2', 'step8.3' => 'expect8.3', 'step8.4' => 'expect8.4', 'step8.5' => 'expect8.5'),
        'group9' => array('step9.1' => 'expect9.1', 'step9.2' => 'expect9.2', 'step9.3' => 'expect9.3', 'step9.4' => 'expect9.4', 'step9.5' => 'expect9.5'),
        'group10' => array('group10.1' => array('step10.1.1' => 'expect10.1.1', 'step10.1.2' => 'expect10.1.2', 'step10.1.3' => 'expect10.1.3', 'step10.1.4' => 'expect10.1.4'), 'step10.2' => 'expect10.2'),
        'step11'  => 'expect11',
        'group12' => array('group12.1' => array('step12.1.1' => 'expect12.1.1', 'step12.1.2' => 'expect12.1.2', 'step12.1.3' => 'expect12.1.3', 'step12.1.4' => 'expect12.1.4'), 'step12.2' => 'expect12.2'),));
r($tester->editTestCase($url, $testcase)) && p('message,status') && e('编辑测试用例成功,SUCCESS'); //验证编辑测试用例