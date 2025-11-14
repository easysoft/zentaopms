#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getImportBugs();
timeout=0
cid=16429

- 步骤1:正常浏览模式获取产品1和2的bugs @6
- 步骤2:正常浏览模式获取产品1的bugs @3
- 步骤3:正常浏览模式获取产品3的bugs @3
- 步骤4:正常浏览模式获取空产品列表的bugs @0
- 步骤5:验证返回bug数量与预期相符 @6

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$bug = zenData('bug');
$bug->loadYaml('zt_bug_getimportbugs', false, 2)->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->status->range('normal');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-5');
$project->type->range('project{1},sprint{4}');
$project->name->range('项目1,执行1,执行2,执行3,执行4');
$project->status->range('doing');
$project->parent->range('0,1,1,1,1');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('2');
$projectProduct->product->range('1,2,3');
$projectProduct->branch->range('0');
$projectProduct->gen(3);

zenData('user')->gen(5);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$executionZenTest = new executionZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($executionZenTest->getImportBugsTest(2, array(1, 2), 'all', 0)) && p() && e('6'); // 步骤1:正常浏览模式获取产品1和2的bugs
r($executionZenTest->getImportBugsTest(2, array(1), 'all', 0)) && p() && e('3'); // 步骤2:正常浏览模式获取产品1的bugs
r($executionZenTest->getImportBugsTest(2, array(3), 'all', 0)) && p() && e('3'); // 步骤3:正常浏览模式获取产品3的bugs
r($executionZenTest->getImportBugsTest(2, array(), 'all', 0)) && p() && e('0'); // 步骤4:正常浏览模式获取空产品列表的bugs
r($executionZenTest->getImportBugsTest(2, array(1, 2), 'all', 0)) && p() && e('6'); // 步骤5:验证返回bug数量与预期相符