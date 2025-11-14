#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCasesForShowImport();
timeout=0
cid=19079

- 步骤1：新增用例情况，返回结果数量 @1
- 步骤2：更新用例情况，存在变更 @1
- 步骤3：更新用例情况，无变更 @0
- 步骤4：处理步骤变更情况 @1
- 步骤5：项目模式下的用例创建 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-5');
$table->product->range('1{5}');
$table->title->range('用例1,用例2,用例3,用例4,用例5');
$table->version->range('1,1,2,2,1');
$table->story->range('1-3,1,2');
$table->gen(5);

$storyTable = zenData('story');
$storyTable->id->range('1-3');
$storyTable->product->range('1{3}');
$storyTable->version->range('1{3}');
$storyTable->gen(3);

$stepTable = zenData('casestep');
$stepTable->id->range('1-10');
$stepTable->case->range('1-5{2}');
$stepTable->version->range('1{5},2{5}');
$stepTable->type->range('step{10}');
$stepTable->desc->range('步骤1,步骤2{9}');
$stepTable->expect->range('期望1,期望2{9}');
$stepTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->buildCasesForShowImportTest(1, true, array())) && p() && e('1'); // 步骤1：新增用例情况，返回结果数量
r($testcaseTest->buildCasesForShowImportTest(1, false, array('1' => array('rawID' => '1', 'title' => '修改后用例1')))) && p() && e('1'); // 步骤2：更新用例情况，存在变更
r($testcaseTest->buildCasesForShowImportTest(1, false, array('1' => array('rawID' => '1', 'title' => '用例1')))) && p() && e('0'); // 步骤3：更新用例情况，无变更
r($testcaseTest->buildCasesForShowImportTest(1, false, array('1' => array('rawID' => '1', 'steps' => '新步骤')))) && p() && e('1'); // 步骤4：处理步骤变更情况
r($testcaseTest->buildCasesForShowImportTest(1, true, array(), 'project')) && p() && e('1'); // 步骤5：项目模式下的用例创建