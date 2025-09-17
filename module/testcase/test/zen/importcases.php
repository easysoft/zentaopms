#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::importCases();
timeout=0
cid=0

- 步骤1：正常情况 @rray()
- 步骤2：边界值第0条的title属性 @新导入用例
- 步骤3：异常输入第0条的title属性 @更新的测试用例标题
- 步骤4：权限验证第0条的title属性 @产品不匹配的用例
- 步骤5：业务规则
 - 第0条的title属性 @新建用例测试
 - 第0条的1:title属性 @更新用例测试

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->project->range('1{5}');
$case->product->range('1{5}');
$case->execution->range('1{5}');
$case->branch->range('0{5}');
$case->lib->range('0{5}');
$case->module->range('1{5}');
$case->story->range('0{5}');
$case->storyVersion->range('1{5}');
$case->title->range('原始测试用例1,原始测试用例2,原始测试用例3,原始测试用例4,原始测试用例5');
$case->pri->range('3{5}');
$case->type->range('feature{5}');
$case->status->range('normal{5}');
$case->version->range('1{5}');
$case->openedBy->range('admin{5}');
$case->openedDate->range('`2024-01-01 10:00:00`{5}');
$case->lastEditedBy->range('admin{5}');
$case->lastEditedDate->range('`2024-01-01 10:00:00`{5}');
$case->deleted->range('0{5}');
$case->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目A,项目B,项目C');
$project->type->range('project{3}');
$project->status->range('doing{3}');
$project->deleted->range('0{3}');
$project->gen(3);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1{5}');
$module->name->range('模块A,模块B,模块C,模块D,模块E');
$module->type->range('story{5}');
$module->deleted->range('0{5}');
$module->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：导入空用例数组
$emptyCases = array();
r($testcaseTest->importCasesTest($emptyCases)) && p() && e(array()); // 步骤1：正常情况

// 测试步骤2：导入新测试用例（无ID）
$newCases = array();
$newCase = new stdClass();
$newCase->product = 1;
$newCase->title = '新导入用例';
$newCase->type = 'feature';
$newCase->pri = 3;
$newCase->status = 'normal';
$newCase->openedBy = 'admin';
$newCase->openedDate = '2024-01-01 10:00:00';
$newCases[] = $newCase;
r($testcaseTest->importCasesTest($newCases)) && p('0:title') && e('新导入用例'); // 步骤2：边界值

// 测试步骤3：导入已有测试用例（有ID）进行更新
$updateCases = array();
$updateCase = new stdClass();
$updateCase->id = 1;
$updateCase->product = 1;
$updateCase->title = '更新的测试用例标题';
$updateCase->type = 'feature';
$updateCase->pri = 1;
$updateCase->status = 'normal';
$updateCase->lastEditedBy = 'admin';
$updateCase->lastEditedDate = '2024-01-01 10:00:00';
$updateCases[] = $updateCase;
r($testcaseTest->importCasesTest($updateCases)) && p('0:title') && e('更新的测试用例标题'); // 步骤3：异常输入

// 测试步骤4：导入产品不匹配的用例（应跳过）
$mismatchCases = array();
$mismatchCase = new stdClass();
$mismatchCase->id = 1;
$mismatchCase->product = 2;  // 不匹配现有用例的产品
$mismatchCase->title = '产品不匹配的用例';
$mismatchCase->type = 'feature';
$mismatchCase->pri = 3;
$mismatchCase->status = 'normal';
$mismatchCase->lastEditedBy = 'admin';
$mismatchCase->lastEditedDate = '2024-01-01 10:00:00';
$mismatchCases[] = $mismatchCase;
r($testcaseTest->importCasesTest($mismatchCases)) && p('0:title') && e('产品不匹配的用例'); // 步骤4：权限验证

// 测试步骤5：导入多个用例的混合场景
$mixedCases = array();
$newCase2 = new stdClass();
$newCase2->product = 1;
$newCase2->title = '新建用例测试';
$newCase2->type = 'feature';
$newCase2->pri = 2;
$newCase2->status = 'normal';
$newCase2->openedBy = 'admin';
$newCase2->openedDate = '2024-01-01 10:00:00';

$updateCase2 = new stdClass();
$updateCase2->id = 2;
$updateCase2->product = 1;
$updateCase2->title = '更新用例测试';
$updateCase2->type = 'feature';
$updateCase2->pri = 1;
$updateCase2->status = 'normal';
$updateCase2->lastEditedBy = 'admin';
$updateCase2->lastEditedDate = '2024-01-01 10:00:00';

$mixedCases[] = $newCase2;
$mixedCases[] = $updateCase2;
r($testcaseTest->importCasesTest($mixedCases)) && p('0:title,1:title') && e('新建用例测试,更新用例测试'); // 步骤5：业务规则