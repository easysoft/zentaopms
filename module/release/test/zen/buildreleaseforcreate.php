#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildReleaseForCreate();
timeout=0
cid=18023

- 步骤1：正常情况
 - 属性product @1
 - 属性branch @0
- 步骤2：shadow产品处理属性product @4
- 步骤3：无效产品ID属性product @999
- 步骤4：分支产品
 - 属性product @2
 - 属性branch @1
- 步骤5：指定项目
 - 属性product @3
 - 属性project @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('测试产品{1-5}');
$product->code->range('TEST{1-5}');
$product->shadow->range('0{3},1{2}');
$product->type->range('normal{3},branch{2}');
$product->status->range('normal');
$product->PO->range('admin');
$product->createdBy->range('admin');
$product->createdDate->range('`2024-01-01 00:00:00`');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('project');
$project->status->range('wait{2},doing{2},done{1}');
$project->begin->range('`2024-01-01`');
$project->end->range('`2024-12-31`');
$project->acl->range('open');
$project->openedBy->range('admin');
$project->openedDate->range('`2024-01-01 00:00:00`');
$project->gen(5);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-5');
$projectproduct->product->range('1-5');
$projectproduct->branch->range('0');
$projectproduct->plan->range('0');
$projectproduct->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseZenTest();

// 模拟POST数据和form配置
$_POST = array();
$_POST['name'] = '测试发布v1.0';
$_POST['build'] = '1';
$_POST['status'] = 'wait';
$_POST['date'] = '2024-12-31';
$_POST['desc'] = '测试发布描述';
$_POST['newSystem'] = false;

// 加载form配置
include dirname(__FILE__, 3) . '/config/form.php';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($releaseTest->buildReleaseForCreateTest(1, 0, 1)) && p('product,branch') && e('1,0'); // 步骤1：正常情况
r($releaseTest->buildReleaseForCreateTest(4, 0, 0)) && p('product') && e('4'); // 步骤2：shadow产品处理
r($releaseTest->buildReleaseForCreateTest(999, 0, 0)) && p('product') && e('999'); // 步骤3：无效产品ID
r($releaseTest->buildReleaseForCreateTest(2, 1, 2)) && p('product,branch') && e('2,1'); // 步骤4：分支产品
r($releaseTest->buildReleaseForCreateTest(3, 0, 3)) && p('product,project') && e('3,3'); // 步骤5：指定项目