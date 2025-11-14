#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildLinkBugsSearchForm();
timeout=0
cid=19082

- 步骤1：正常情况下构建关联bug搜索表单属性executed @1
- 步骤2：project tab下的objectID设置属性objectID @1
- 步骤3：execution tab下的objectID设置属性objectID @2
- 步骤4：瀑布项目删除plan字段属性planFieldRemoved @1
- 步骤5：总是删除product字段属性productFieldRemoved @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$case = zenData('case');
$case->id->range('1-5');
$case->product->range('1-3');
$case->project->range('1-3');
$case->execution->range('1-3');
$case->title->range('测试用例{1-5}');
$case->status->range('normal');
$case->gen(5);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目{1-3}');
$project->hasProduct->range('0,1');
$project->model->range('waterfall,scrum');
$project->type->range('project');
$project->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品{1-3}');
$product->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseZenTest = new testcaseZenTest();

// 5. 测试步骤
r($testcaseZenTest->buildLinkBugsSearchFormTest(1, 0, 'qa', '')) && p('executed') && e('1'); // 步骤1：正常情况下构建关联bug搜索表单
r($testcaseZenTest->buildLinkBugsSearchFormTest(1, 0, 'project', '')) && p('objectID') && e('1'); // 步骤2：project tab下的objectID设置
r($testcaseZenTest->buildLinkBugsSearchFormTest(2, 0, 'execution', '')) && p('objectID') && e('2'); // 步骤3：execution tab下的objectID设置
r($testcaseZenTest->buildLinkBugsSearchFormTest(3, 0, 'project', '')) && p('planFieldRemoved') && e('1'); // 步骤4：瀑布项目删除plan字段
r($testcaseZenTest->buildLinkBugsSearchFormTest(4, 0, 'qa', '')) && p('productFieldRemoved') && e('1'); // 步骤5：总是删除product字段