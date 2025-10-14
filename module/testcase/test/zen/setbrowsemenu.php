#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setBrowseMenu();
timeout=0
cid=0

- 步骤1：在project标签下有效projectID的场景
 -  @1
 - 属性1 @all
- 步骤2：在project标签下projectID为0的场景
 -  @2
 - 属性1 @test
- 步骤3：在qa标签下设置菜单
 -  @3
 - 属性1 @0
- 步骤4：测试branch参数为字符串时的处理
 -  @4
 - 属性1 @all
- 步骤5：测试branch参数为数字时的处理
 -  @5
 - 属性1 @all

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal{10}');
$product->status->range('normal{10}');
$product->deleted->range('0{10}');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{10}');
$project->status->range('wait{5},doing{3},suspended{1},closed{1}');
$project->hasProduct->range('1{8},0{2}');
$project->deleted->range('0{10}');
$project->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-8');
$projectProduct->product->range('1-8');
$projectProduct->branch->range('0{8}');
$projectProduct->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->setBrowseMenuTest(1, 'all', 1, 'project', 1)) && p('0,1') && e('1,all'); // 步骤1：在project标签下有效projectID的场景
r($testcaseTest->setBrowseMenuTest(2, 'test', 0, 'project', 0)) && p('0,1') && e('2,test'); // 步骤2：在project标签下projectID为0的场景
r($testcaseTest->setBrowseMenuTest(3, '0', 0, 'qa', 0)) && p('0,1') && e('3,0'); // 步骤3：在qa标签下设置菜单
r($testcaseTest->setBrowseMenuTest(4, 'main', 2, 'project', 2)) && p('0,1') && e('4,all'); // 步骤4：测试branch参数为字符串时的处理
r($testcaseTest->setBrowseMenuTest(5, 1, 3, 'project', 3)) && p('0,1') && e('5,all'); // 步骤5：测试branch参数为数字时的处理