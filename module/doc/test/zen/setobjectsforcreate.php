#!/usr/bin/env php
<?php

/**

title=测试 docZen::setObjectsForCreate();
timeout=0
cid=0

- 步骤1：project类型返回空对象数组 @0
- 步骤2：product类型返回产品列表第objects条的1属性 @产品1
- 步骤3：mine类型返回ACL列表第aclList条的private属性 @个人
- 步骤4：execution类型返回空对象数组 @0
- 步骤5：api类型返回产品列表第objects条的1属性 @产品1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{5},sprint{5}');
$project->isTpl->range('0{8},1{2}');
$project->status->range('wait{3},doing{4},suspended{1},closed{2}');
$project->deleted->range('0{9},1{1}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{1},platform{1}');
$product->status->range('normal{4},closed{1}');
$product->deleted->range('0');
$product->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->type->range('project,execution,product,custom,mine');
$doclib->name->range('项目文档库,执行文档库,产品文档库,团队文档库,我的文档库');
$doclib->project->range('1,0,0,0,0');
$doclib->execution->range('0,6,0,0,0');
$doclib->product->range('0,0,1,0,0');
$doclib->gen(5);

$execution = zenData('project');
$execution->id->range('6-15');
$execution->project->range('1{5},2{5}');
$execution->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$execution->type->range('sprint{6},kanban{2},stage{2}');
$execution->grade->range('1');
$execution->status->range('wait{3},doing{4},suspended{1},closed{2}');
$execution->deleted->range('0');
$execution->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($docTest->setObjectsForCreateTest('project', null, 'unclosedProject', 0)['objects'])) && p() && e(0); // 步骤1：project类型返回空对象数组
r($docTest->setObjectsForCreateTest('product', null, '', 0)) && p('objects:1') && e('产品1'); // 步骤2：product类型返回产品列表  
r($docTest->setObjectsForCreateTest('mine', null, '', 0)) && p('aclList:private') && e('个人'); // 步骤3：mine类型返回ACL列表
r(count($docTest->setObjectsForCreateTest('execution', (object)array('type' => 'execution', 'execution' => 7), '', 0)['objects'])) && p() && e(0); // 步骤4：execution类型返回空对象数组
r($docTest->setObjectsForCreateTest('api', null, '', 0)) && p('objects:1') && e('产品1'); // 步骤5：api类型返回产品列表