#!/usr/bin/env php
<?php

/**

title=测试 biModel::getScopeOptions();
timeout=0
cid=0

- 步骤1：测试user类型返回用户选项数组属性admin @管理员
- 步骤2：测试product类型返回产品选项数组属性1 @产品1
- 步骤3：测试project类型返回项目选项数组（可能为空） @0
- 步骤4：测试execution类型返回数组 @1
- 步骤5：测试dept类型返回部门选项数组属性1 @/部门1
- 步骤6：测试user.status语言包类型返回状态选项数组属性active @正常
- 步骤7：测试无效类型返回空数组 @0
- 步骤8：测试空类型返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0');
$user->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->deleted->range('0');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->type->range('project');
$project->deleted->range('0');
$project->status->range('doing');
$project->parent->range('0');
$project->path->range(',1,',',2,',',3,');
$project->vision->range('rnd');
$project->begin->range('`2023-01-01`');
$project->end->range('`2023-12-31`');
$project->model->range('scrum');
$project->gen(3);

$execution = zenData('project');
$execution->id->range('11-13');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint');
$execution->deleted->range('0');
$execution->status->range('doing');
$execution->parent->range('1,2,3');
$execution->path->range(',1,11,',',2,12,',',3,13,');
$execution->vision->range('rnd');
$execution->begin->range('`2023-01-01`');
$execution->end->range('`2023-12-31`');
$execution->gen(3);

$dept = zenData('dept');
$dept->id->range('1-3');
$dept->name->range('部门1,部门2,部门3');
$dept->parent->range('0');
$dept->path->range('1,2,3');
$dept->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getScopeOptionsTest('user')) && p('admin') && e('管理员'); // 步骤1：测试user类型返回用户选项数组
r($biTest->getScopeOptionsTest('product')) && p('1') && e('产品1'); // 步骤2：测试product类型返回产品选项数组
r(count($biTest->getScopeOptionsTest('project'))) && p() && e('0'); // 步骤3：测试project类型返回项目选项数组（可能为空）
r(is_array($biTest->getScopeOptionsTest('execution'))) && p() && e('1'); // 步骤4：测试execution类型返回数组
r($biTest->getScopeOptionsTest('dept')) && p('1') && e('/部门1'); // 步骤5：测试dept类型返回部门选项数组
r($biTest->getScopeOptionsTest('user.status')) && p('active') && e('正常'); // 步骤6：测试user.status语言包类型返回状态选项数组
r(count($biTest->getScopeOptionsTest('invalid'))) && p() && e('0'); // 步骤7：测试无效类型返回空数组
r(count($biTest->getScopeOptionsTest(''))) && p() && e('0'); // 步骤8：测试空类型返回空数组