#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBrowse();
timeout=0
cid=0

- 步骤1：正常情况属性productID @1
- 步骤2：无项目ID属性projectID @0
- 步骤3：无模块ID属性moduleName @所有模块
- 步骤4：分支为all属性browseType @all
- 步骤5：不同浏览类型属性param @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,测试产品{5}');
$product->type->range('normal{8},branch{2}');
$product->status->range('normal{8},closed{2}');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,测试项目{5}');
$project->model->range('scrum{4},waterfall{3},kanban{3}');
$project->type->range('project{6},sprint{4}');
$project->status->range('wait{2},doing{4},suspended{2},done{2}');
$project->deleted->range('0');
$project->gen(10);

$module = zenData('module');
$module->id->range('1-20');
$module->root->range('1-5');
$module->name->range('需求管理,用户管理,系统设置,测试模块,接口模块,前端模块,后端模块,数据库模块,安全模块,性能模块{10}');
$module->parent->range('0{10},1{5},2{3},3{2}');
$module->type->range('case{15},story{5}');
$module->deleted->range('0');
$module->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignForBrowseTest(1, '0', 'all', 1, 0, 1, 0, 'feature')) && p('productID') && e('1'); // 步骤1：正常情况
r($testcaseTest->assignForBrowseTest(2, '0', 'bysearch', 0, 0, 2, 0, 'performance')) && p('projectID') && e('0'); // 步骤2：无项目ID
r($testcaseTest->assignForBrowseTest(3, '0', 'bymodule', 2, 0, 0, 0, 'config')) && p('moduleName') && e('所有模块'); // 步骤3：无模块ID
r($testcaseTest->assignForBrowseTest(4, 'all', 'all', 3, 0, 3, 1, 'interface')) && p('browseType') && e('all'); // 步骤4：分支为all
r($testcaseTest->assignForBrowseTest(5, '1', 'assignedtome', 4, 10, 4, 2, 'unit')) && p('param') && e('10'); // 步骤5：不同浏览类型