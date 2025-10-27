#!/usr/bin/env php
<?php

/**

title=测试 programZen::getKanbanList();
timeout=0
cid=0

- 步骤1：测试获取我的看板列表，返回数组长度 @1
- 步骤2：测试获取所有看板列表，返回数组长度 @1
- 步骤3：测试空参数获取看板列表（默认为my），返回数组长度 @1
- 步骤4：测试无效参数获取看板列表，返回数组长度 @1
- 步骤5：测试返回结果第0个元素包含key字段第0条的key属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目集1,项目集2,项目集3,项目集4,项目集5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('program{5},project{5}');
$project->status->range('doing{6},wait{2},closed{2}');
$project->parent->range('0{5},1{2},2{2},3{1}');
$project->deleted->range('0{10}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-8');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8');
$product->program->range('1{2},2{2},0{4}');
$product->deleted->range('0{8}');
$product->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programTest = new programTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($programTest->getKanbanListTest('my')) && p() && e('1'); // 步骤1：测试获取我的看板列表，返回数组长度
r($programTest->getKanbanListTest('all')) && p() && e('1'); // 步骤2：测试获取所有看板列表，返回数组长度
r($programTest->getKanbanListTest()) && p() && e('1'); // 步骤3：测试空参数获取看板列表（默认为my），返回数组长度
r($programTest->getKanbanListTest('invalid')) && p() && e('1'); // 步骤4：测试无效参数获取看板列表，返回数组长度
r($programTest->getKanbanListArrayTest('my')) && p('0:key') && e('0'); // 步骤5：测试返回结果第0个元素包含key字段