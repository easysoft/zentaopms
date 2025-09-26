#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getRelatedObjects();
timeout=0
cid=0

- 步骤1：无bug数据时product对象(2个空选项) @2
- 步骤2：无bug数据时project对象(2个空选项) @2
- 步骤3：无bug数据时build对象(2空+1trunk) @3
- 步骤4：openedBuild转build处理 @3
- 步骤5：不存在类型返回基础选项 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-5');
$bug->project->range('1-5');
$bug->openedBuild->range('1-3,trunk');
$bug->resolvedBuild->range('1-3,trunk');
$bug->deleted->range('0');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->gen(5);

$build = zenData('build');
$build->id->range('1-3');
$build->name->range('版本1,版本2,版本3');
$build->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->getRelatedObjectsTest('product', 'id,name')) && p() && e('2'); // 步骤1：无bug数据时product对象(2个空选项)
r($bugTest->getRelatedObjectsTest('project', 'id,name')) && p() && e('2'); // 步骤2：无bug数据时project对象(2个空选项)
r($bugTest->getRelatedObjectsTest('build', 'id,name')) && p() && e('3'); // 步骤3：无bug数据时build对象(2空+1trunk)
r($bugTest->getRelatedObjectsTest('openedBuild', 'id,name')) && p() && e('3'); // 步骤4：openedBuild转build处理
r($bugTest->getRelatedObjectsTest('nonexistent', 'id,name')) && p() && e('2'); // 步骤5：不存在类型返回基础选项