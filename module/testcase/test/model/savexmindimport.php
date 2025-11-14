#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::saveXmindImport();
timeout=0
cid=19022

- 测试1属性result @success
- 测试2属性result @success
- 测试3属性result @success
- 测试4属性result @success
- 测试5属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5{5}');
$product->code->range('product1-10');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->password->range('123456');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

su('admin');

$testcaseTest = new testcaseTest();

// 测试1: 正常导入1个场景和1个测试用例
$scenes1 = array(
    array(
        'tmpId' => 'tmp_scene_1',
        'tmpPId' => 0,
        'name' => '场景测试1',
        'product' => 1,
        'branch' => 0,
        'module' => 0
    )
);
$testcases1 = array(
    (object)array(
        'tmpPId' => 'tmp_scene_1',
        'product' => 1,
        'branch' => 0,
        'module' => 0,
        'title' => '测试用例1',
        'type' => 'feature',
        'pri' => 3,
        'status' => 'wait',
        'precondition' => '',
        'keywords' => '',
        'steps' => array('步骤1'),
        'expects' => array('期望1'),
        'stepType' => array('step')
    )
);
r($testcaseTest->saveXmindImportTest($scenes1, $testcases1)) && p('result') && e('success'); // 测试1

// 测试2: 导入多个场景和多个测试用例
$scenes2 = array(
    array(
        'tmpId' => 'tmp_scene_2',
        'tmpPId' => 0,
        'name' => '场景测试2',
        'product' => 1,
        'branch' => 0,
        'module' => 0
    ),
    array(
        'tmpId' => 'tmp_scene_3',
        'tmpPId' => 0,
        'name' => '场景测试3',
        'product' => 1,
        'branch' => 0,
        'module' => 0
    )
);
$testcases2 = array(
    (object)array(
        'tmpPId' => 'tmp_scene_2',
        'product' => 1,
        'branch' => 0,
        'module' => 0,
        'title' => '测试用例2',
        'type' => 'feature',
        'pri' => 3,
        'status' => 'wait',
        'precondition' => '',
        'keywords' => '',
        'steps' => array('步骤1'),
        'expects' => array('期望1'),
        'stepType' => array('step')
    ),
    (object)array(
        'tmpPId' => 'tmp_scene_3',
        'product' => 1,
        'branch' => 0,
        'module' => 0,
        'title' => '测试用例3',
        'type' => 'feature',
        'pri' => 2,
        'status' => 'wait',
        'precondition' => '',
        'keywords' => '',
        'steps' => array('步骤1'),
        'expects' => array('期望1'),
        'stepType' => array('step')
    )
);
r($testcaseTest->saveXmindImportTest($scenes2, $testcases2)) && p('result') && e('success'); // 测试2

// 测试3: 导入场景但没有测试用例
$scenes3 = array(
    array(
        'tmpId' => 'tmp_scene_4',
        'tmpPId' => 0,
        'name' => '场景测试4',
        'product' => 2,
        'branch' => 0,
        'module' => 0
    )
);
$testcases3 = array();
r($testcaseTest->saveXmindImportTest($scenes3, $testcases3)) && p('result') && e('success'); // 测试3

// 测试4: 导入空场景和空测试用例数组
$scenes4 = array();
$testcases4 = array();
r($testcaseTest->saveXmindImportTest($scenes4, $testcases4)) && p('result') && e('success'); // 测试4

// 测试5: 导入场景和测试用例时验证product字段
$scenes5 = array(
    array(
        'tmpId' => 'tmp_scene_5',
        'tmpPId' => 0,
        'name' => '场景测试5',
        'product' => 2,
        'branch' => 0,
        'module' => 0
    )
);
$testcases5 = array(
    (object)array(
        'tmpPId' => 'tmp_scene_5',
        'product' => 2,
        'branch' => 0,
        'module' => 0,
        'title' => '测试用例5',
        'type' => 'performance',
        'pri' => 2,
        'status' => 'normal',
        'precondition' => '前置条件',
        'keywords' => 'keyword',
        'steps' => array('步骤1', '步骤2'),
        'expects' => array('期望1', '期望2'),
        'stepType' => array('step', 'step')
    )
);
r($testcaseTest->saveXmindImportTest($scenes5, $testcases5)) && p('result') && e('success'); // 测试5