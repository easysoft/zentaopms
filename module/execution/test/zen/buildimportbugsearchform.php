#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildImportBugSearchForm();
timeout=0
cid=0

- 步骤1:正常执行和查询ID,包含产品和项目
 - 属性success @1
 - 属性queryID @1
- 步骤2:不同查询ID,多执行场景属性queryID @2
- 步骤3:无产品场景属性hasProducts @0
- 步骤4:无产品项目特殊处理属性hasProductField @0
- 步骤5:无效执行ID属性success @~~

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$project = zenData('project');
$project->id->range('1-10');
$project->type->range('project{4},sprint{6}');
$project->name->range('项目1,项目2,项目3,无产品项目,执行1,执行2,执行3,执行4,执行5,执行6');
$project->status->range('doing{4},wait{6}');
$project->parent->range('0{4},1,1,2,2,3,4');
$project->project->range('0{4},1,1,2,2,3,4');
$project->hasProduct->range('1,1,1,0,1{6}');
$project->model->range('scrum,waterfall,agileplus,scrum');
$project->multiple->range('1{4},0,1,1,0,0,1');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal{5}');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('5-7');
$projectProduct->product->range('1-3');
$projectProduct->gen(3);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$executionZenTest = new executionZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($executionZenTest->buildImportBugSearchFormTest(5, 1, array(1 => '产品1', 2 => '产品2'), array(), array(1 => '项目1'))) && p('success,queryID') && e('1,1'); // 步骤1:正常执行和查询ID,包含产品和项目
r($executionZenTest->buildImportBugSearchFormTest(6, 2, array(1 => '产品1'), array(5 => '执行1', 6 => '执行2'), array(2 => '项目2'))) && p('queryID') && e('2'); // 步骤2:不同查询ID,多执行场景
r($executionZenTest->buildImportBugSearchFormTest(5, 1, array(), array(), array(1 => '项目1'))) && p('hasProducts') && e('0'); // 步骤3:无产品场景
r($executionZenTest->buildImportBugSearchFormTest(10, 1, array(), array(), array(4 => '无产品项目'))) && p('hasProductField') && e('0'); // 步骤4:无产品项目特殊处理
r($executionZenTest->buildImportBugSearchFormTest(999, 1, array(), array(), array())) && p('success') && e('~~'); // 步骤5:无效执行ID