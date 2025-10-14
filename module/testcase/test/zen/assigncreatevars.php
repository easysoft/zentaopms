#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCreateVars();
timeout=0
cid=0

- 步骤1：正常产品ID调用
 - 属性productSet @1
 - 属性hasView @1
 - 属性viewProduct @1
- 步骤2：带分支参数调用
 - 属性productSet @1
 - 属性viewBranch @main
- 步骤3：来源为project调用
 - 属性productSet @1
 - 属性viewProjectID @1
- 步骤4：来源为execution调用
 - 属性productSet @1
 - 属性viewExecutionID @1
- 步骤5：带故事ID调用
 - 属性productSet @1
 - 属性hasView @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品{1-10}');
$product->code->range('P{001-010}');
$product->type->range('normal{7},branch{3}');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('admin');
$product->RD->range('admin');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->createdDate->range('`2023-01-01 10:00:00`');
$product->createdVersion->range('1.0');
$product->deleted->range('0');
$product->gen(10);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('1,1,1,2,2');
$branch->name->range('main,develop,feature,release,hotfix');
$branch->status->range('active');
$branch->createdDate->range('`2023-01-01`');
$branch->deleted->range('0');
$branch->gen(5);

$story = zenData('story');
$story->id->range('1-8');
$story->product->range('1{5},2{3}');
$story->title->range('用户登录功能,产品管理功能,测试用例管理,缺陷跟踪功能,项目管理功能,报表统计功能,系统设置功能,权限管理功能');
$story->type->range('story');
$story->status->range('active');
$story->openedBy->range('admin');
$story->openedDate->range('`2023-01-01 10:00:00`');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignCreateVarsTest(1, '', 0, '', 0, 0)) && p('productSet,hasView,viewProduct') && e('1,1,1'); // 步骤1：正常产品ID调用
r($testcaseTest->assignCreateVarsTest(1, 'main', 1821, '', 0, 0)) && p('productSet,viewBranch') && e('1,main'); // 步骤2：带分支参数调用
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'project', 1, 0)) && p('productSet,viewProjectID') && e('1,1'); // 步骤3：来源为project调用
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'execution', 1, 0)) && p('productSet,viewExecutionID') && e('1,1'); // 步骤4：来源为execution调用
r($testcaseTest->assignCreateVarsTest(1, '', 0, '', 0, 1)) && p('productSet,hasView') && e('1,1'); // 步骤5：带故事ID调用