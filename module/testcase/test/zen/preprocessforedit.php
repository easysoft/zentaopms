#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::preProcessForEdit();
timeout=0
cid=0

- 测试空步骤数组,期望自动添加一个空步骤第steps[0]条的type属性 @step
- 测试未设置steps属性,期望自动添加一个空步骤第steps[0]条的type属性 @step
- 测试单个步骤,期望保持原有步骤第steps[0]条的desc属性 @Test step description
- 测试多个步骤,期望保持原有步骤第steps[0]条的desc属性 @Step 1 description
- 测试分组步骤,期望保持原有步骤第steps[0]条的type属性 @group
- 测试空步骤初始化后的长度 @1
- 测试初始化步骤的desc属性为空第steps[0]条的desc属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

// 构造测试用例对象 - 空步骤数组
$case1 = new stdclass();
$case1->id = 1;
$case1->title = "Test Case 1";
$case1->steps = array();

// 构造测试用例对象 - 未设置steps属性
$case2 = new stdclass();
$case2->id = 2;
$case2->title = "Test Case 2";

// 构造测试用例对象 - 单个步骤
$case3 = new stdclass();
$case3->id = 3;
$case3->title = "Test Case 3";
$step3 = new stdclass();
$step3->type = 'step';
$step3->desc = 'Test step description';
$step3->expect = 'Expected result';
$case3->steps = array($step3);

// 构造测试用例对象 - 多个步骤
$case4 = new stdclass();
$case4->id = 4;
$case4->title = "Test Case 4";
$case4->steps = array();
for($i = 1; $i <= 3; $i++)
{
    $step = new stdclass();
    $step->type = 'step';
    $step->desc = "Step {$i} description";
    $step->expect = "Expected result {$i}";
    $case4->steps[] = $step;
}

// 构造测试用例对象 - 分组步骤
$case5 = new stdclass();
$case5->id = 5;
$case5->title = "Test Case 5";
$step5a = new stdclass();
$step5a->type = 'group';
$step5a->desc = 'Group step';
$step5a->expect = '';
$step5b = new stdclass();
$step5b->type = 'step';
$step5b->desc = 'Normal step';
$step5b->expect = 'Expected';
$case5->steps = array($step5a, $step5b);

// 构造测试用例对象 - 用于验证长度
$case6 = new stdclass();
$case6->id = 6;
$case6->title = "Test Case 6";
$case6->steps = array();

// 构造测试用例对象 - 用于验证默认属性
$case7 = new stdclass();
$case7->id = 7;
$case7->title = "Test Case 7";
$case7->steps = array();

r($testcaseTest->preProcessForEditTest($case1)) && p('steps[0]:type') && e('step'); // 测试空步骤数组,期望自动添加一个空步骤
r($testcaseTest->preProcessForEditTest($case2)) && p('steps[0]:type') && e('step'); // 测试未设置steps属性,期望自动添加一个空步骤
r($testcaseTest->preProcessForEditTest($case3)) && p('steps[0]:desc') && e('Test step description'); // 测试单个步骤,期望保持原有步骤
r($testcaseTest->preProcessForEditTest($case4)) && p('steps[0]:desc') && e('Step 1 description'); // 测试多个步骤,期望保持原有步骤
r($testcaseTest->preProcessForEditTest($case5)) && p('steps[0]:type') && e('group'); // 测试分组步骤,期望保持原有步骤
r(count($testcaseTest->preProcessForEditTest($case6)->steps)) && p() && e('1'); // 测试空步骤初始化后的长度
r($testcaseTest->preProcessForEditTest($case7)) && p('steps[0]:desc') && e('~~'); // 测试初始化步骤的desc属性为空