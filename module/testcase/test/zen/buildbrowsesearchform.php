#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildBrowseSearchForm();
timeout=0
cid=0

- 步骤1：在testcase模块下构建搜索表单属性onMenuBar @yes
- 步骤2：在非testcase模块下构建搜索表单属性onMenuBar @~~
- 步骤3：使用有效productID构建搜索表单属性searchProductsCount @5
- 步骤4：使用有效queryID构建搜索表单属性searchFieldsCount @20
- 步骤5：使用有效projectID构建搜索表单属性searchFieldsCount @20

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目A,项目B,项目C');
$project->type->range('project');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(3);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-5');
$case->project->range('1-3');
$case->title->range('测试用例{1-10}');
$case->status->range('normal');
$case->deleted->range('0');
$case->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseTest();

// 5. 测试步骤
r($testcaseTest->buildBrowseSearchFormTest(1, 0, 1, 'testcase-browse-1.html', 'testcase')) && p('onMenuBar') && e('yes'); // 步骤1：在testcase模块下构建搜索表单
r($testcaseTest->buildBrowseSearchFormTest(1, 0, 1, 'testcase-browse-1.html', 'other')) && p('onMenuBar') && e('~~'); // 步骤2：在非testcase模块下构建搜索表单
r($testcaseTest->buildBrowseSearchFormTest(1, 0, 0, 'testcase-browse-1.html', 'testcase')) && p('searchProductsCount') && e('5'); // 步骤3：使用有效productID构建搜索表单
r($testcaseTest->buildBrowseSearchFormTest(2, 1, 0, 'testcase-browse-2.html', 'testcase')) && p('searchFieldsCount') && e('20'); // 步骤4：使用有效queryID构建搜索表单
r($testcaseTest->buildBrowseSearchFormTest(3, 0, 2, 'testcase-browse-3.html', 'testcase')) && p('searchFieldsCount') && e('20'); // 步骤5：使用有效projectID构建搜索表单