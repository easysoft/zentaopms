#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildDataForImportToLib();
timeout=0
cid=19081

- 步骤1：检查返回的cases数组是否存在 @1
- 步骤2：检查返回的steps数组是否存在 @1
- 步骤3：检查返回的files数组是否存在 @1
- 步骤4：检查返回数组包含3个元素 @3
- 步骤5：检查所有元素都是数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->product->range('1{3},2{2}');
$case->lib->range('0');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->precondition->range('前置条件1,前置条件2,前置条件3,前置条件4,前置条件5');
$case->keywords->range('关键词1,关键词2,关键词3,关键词4,关键词5');
$case->pri->range('1-3');
$case->type->range('feature{3},performance{2}');
$case->stage->range('unittest{2},functest{3}');
$case->status->range('normal{4},wait{1}');
$case->fromCaseID->range('0');
$case->fromCaseVersion->range('1');
$case->color->range('');
$case->order->range('1-5');
$case->module->range('1-3');
$case->version->range('1');
$case->deleted->range('0');
$case->gen(5);

$casestep = zenData('casestep');
$casestep->id->range('1-15');
$casestep->parent->range('0');
$casestep->case->range('1{3},2{3},3{3},4{3},5{3}');
$casestep->version->range('1');
$casestep->type->range('step');
$casestep->desc->range('步骤描述1,步骤描述2,步骤描述3');
$casestep->expect->range('期望结果1,期望结果2,期望结果3');
$casestep->gen(15);

$file = zenData('file');
$file->id->range('1-5');
$file->pathname->range('/files/test1.txt,/files/test2.txt,/files/test3.txt,/files/test4.txt,/files/test5.txt');
$file->title->range('测试文件1,测试文件2,测试文件3,测试文件4,测试文件5');
$file->extension->range('txt{3},pdf{2}');
$file->size->range('1024-2048');
$file->objectType->range('testcase');
$file->objectID->range('1{2},2{2},3{1}');
$file->extra->range('');
$file->gen(5);

$caselib = zenData('case');
$caselib->id->range('101-105');
$caselib->lib->range('1{3},2{2}');
$caselib->product->range('0');
$caselib->fromCaseID->range('1{2},2{2},3{1}');
$caselib->title->range('库用例1,库用例2,库用例3,库用例4,库用例5');
$caselib->deleted->range('0');
$caselib->gen(5);

$module = zenData('module');
$module->id->range('1-5');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->type->range('case');
$module->order->range('1-5');
$module->deleted->range('0');
$module->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result = $testcaseTest->buildDataForImportToLibTest(1, 1);
r(isset($result[0])) && p() && e('1'); // 步骤1：检查返回的cases数组是否存在
r(isset($result[1])) && p() && e('1'); // 步骤2：检查返回的steps数组是否存在
r(isset($result[2])) && p() && e('1'); // 步骤3：检查返回的files数组是否存在
r(count($result)) && p() && e('3'); // 步骤4：检查返回数组包含3个元素
r(is_array($result[0]) && is_array($result[1]) && is_array($result[2])) && p() && e('1'); // 步骤5：检查所有元素都是数组