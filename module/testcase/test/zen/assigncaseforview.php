#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCaseForView();
timeout=0
cid=19060

- 步骤1：正常用例来源为testcase
 - 属性executed @1
 - 属性from @testcase
 - 属性taskID @0
 - 属性runID @0
- 步骤2：用例来源为testtask
 - 属性executed @1
 - 属性from @testtask
 - 属性taskID @1
- 步骤3：testtask且有测试结果
 - 属性executed @1
 - 属性from @testtask
 - 属性taskID @2
- 步骤4：模块为0的边界情况
 - 属性executed @1
 - 属性case @1
 - 属性modulePath @1
- 步骤5：场景不存在的情况
 - 属性executed @1
 - 属性case @1
 - 属性scenes @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->loadYaml('case_assigncaseforview', false, 2)->gen(10);

$testrun = zenData('testrun');
$testrun->loadYaml('testrun_assigncaseforview', false, 2)->gen(5);

$testresult = zenData('testresult');
$testresult->loadYaml('testresult_assigncaseforview', false, 2)->gen(3);

$scene = zenData('scene');
$scene->id->range('1-3');
$scene->product->range('1{3}');
$scene->title->range('场景1,场景2,场景3');
$scene->gen(3);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1{5}');
$module->type->range('story{5}');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

$action = zenData('action');
$action->id->range('1-3');
$action->objectType->range('case{3}');
$action->objectID->range('1{3}');
$action->action->range('created,edited,reviewed');
$action->actor->range('admin{3}');
$action->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试用例对象
$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->module = 1;
$case1->scene = 1;
$case1->status = 'normal';
$case1->story = 1;
$case1->storyVersion = 1;
$case1->title = '测试用例1';
$case1->steps = '';

$case2 = new stdClass();
$case2->id = 2;
$case2->product = 1;
$case2->module = 2;
$case2->scene = 2;
$case2->status = 'wait';
$case2->story = 2;
$case2->storyVersion = 1;
$case2->title = '测试用例2';
$case2->steps = '';

$case3 = new stdClass();
$case3->id = 3;
$case3->product = 1;
$case3->module = 3;
$case3->scene = 3;
$case3->status = 'blocked';
$case3->story = 3;
$case3->storyVersion = 1;
$case3->title = '测试用例3';
$case3->steps = '';

$case4 = new stdClass();
$case4->id = 4;
$case4->product = 1;
$case4->module = 0;
$case4->scene = 0;
$case4->status = 'normal';
$case4->story = 0;
$case4->storyVersion = 1;
$case4->title = '测试用例4';
$case4->steps = '';

$case5 = new stdClass();
$case5->id = 5;
$case5->product = 1;
$case5->module = 5;
$case5->scene = 999;
$case5->status = 'normal';
$case5->story = 5;
$case5->storyVersion = 1;
$case5->title = '测试用例5';
$case5->steps = '';

r($testcaseTest->assignCaseForViewTest($case1, 'testcase', 0)) && p('executed,from,taskID,runID') && e('1,testcase,0,0'); // 步骤1：正常用例来源为testcase
r($testcaseTest->assignCaseForViewTest($case2, 'testtask', 1)) && p('executed,from,taskID') && e('1,testtask,1'); // 步骤2：用例来源为testtask
r($testcaseTest->assignCaseForViewTest($case3, 'testtask', 2)) && p('executed,from,taskID') && e('1,testtask,2'); // 步骤3：testtask且有测试结果
r($testcaseTest->assignCaseForViewTest($case4, 'testcase', 0)) && p('executed,case,modulePath') && e('1,1,1'); // 步骤4：模块为0的边界情况
r($testcaseTest->assignCaseForViewTest($case5, 'testcase', 0)) && p('executed,case,scenes') && e('1,1,1'); // 步骤5：场景不存在的情况