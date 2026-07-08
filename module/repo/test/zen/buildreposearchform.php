#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildRepoSearchForm();
timeout=0
cid=18127

- 步骤1：普通空间搜索表单属性queryID @0
- 步骤2：带产品和项目的搜索属性queryID @5
- 步骤3：不在空间内的搜索属性queryID @10
- 步骤4：空产品空项目列表
 - 属性queryID @0
 - 属性onMenuBar @yes
- 步骤5：多产品多项目搜索属性queryID @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal');
$table->status->range('normal');
$table->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->status->range('wait');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 准备测试数据
$products1 = array('1' => '产品1', '2' => '产品2');
$projects1 = array('1' => '项目1', '2' => '项目2');

$products2 = array('1' => '产品1', '2' => '产品2', '3' => '产品3');
$projects2 = array('1' => '项目1', '2' => '项目2', '3' => '项目3');

$products3 = array();
$projects3 = array();

$products4 = array('1' => '产品1', '2' => '产品2', '3' => '产品3', '4' => '产品4', '5' => '产品5');
$projects4 = array('1' => '项目1', '2' => '项目2', '3' => '项目3', '4' => '项目4');

// 5. 测试步骤
r($repoTest->buildRepoSearchFormTest(1, 1, $products1, $projects1, 1, 'id_desc', 20, 1, 0))    && p('queryID') && e('0'); // 步骤1：普通空间搜索表单
r($repoTest->buildRepoSearchFormTest(0, 2, $products2, $projects2, 10, 'id_desc', 50, 1, 5))   && p('queryID') && e('5'); // 步骤2：带产品和项目的搜索
r($repoTest->buildRepoSearchFormTest(0, 0, $products1, $projects1, 100, 'name_asc', 10, 2, 10)) && p('queryID') && e('10'); // 步骤3：不在空间内的搜索
r($repoTest->buildRepoSearchFormTest(1, 5, $products3, $projects3, 5, 'id_asc', 30, 1, 0))     && p('queryID,onMenuBar') && e('0,yes'); // 步骤4：空产品空项目列表
r($repoTest->buildRepoSearchFormTest(0, 3, $products4, $projects4, 50, 'code_desc', 100, 3, 15)) && p('queryID') && e('15'); // 步骤5：多产品多项目搜索
