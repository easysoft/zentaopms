#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/testcase.ui.class.php';
$tester = new testcase();

$testcase = array(
    'caseName'       => 'testcase' . time(),
    'type'           => '安装部署',
    'stage'          => array('单元测试阶段', '功能测试阶段', '集成测试阶段', '系统测试阶段', '冒烟测试阶段', '版本验证阶段'),
    'pri'            => '2',
    'precondition'   => '前置条件测试',
    'steps'          => array(
        'step1'  => 'expect1',
        'group2' => array('step2.1' => 'expect2.1', 'step2.2' => 'expect2.2'),
        'group3' => array('group3.1' => array('step3.3.1' => 'expect3.3.1', 'step3.3.2' => 'expect3.3.2'), 'step3.2' => 'expect3.2')
    ),
);

$project  = array(
    'productID' => 1,
    'branch'    => 0,
    'extra'     => 'moduleID=0'
);

r($tester->createTestCase($project, $testcase)) && p('message,status') && e('创建测试用例成功,SUCCESS'); //验证bug表单页必填项校验
