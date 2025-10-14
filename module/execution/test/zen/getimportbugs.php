#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getImportBugs();
timeout=0
cid=0

- 步骤1：非搜索模式正常情况 @0
- 步骤2：搜索模式使用queryID @0
- 步骤3：搜索模式使用session查询 @0
- 步骤4：空产品列表 @0
- 步骤5：无效executionID边界测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 准备测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1{5},2{3},3{2}');
$bugTable->title->range('Bug标题{10}');
$bugTable->status->range('active{5},resolved{2},postponed{3}');
$bugTable->assignedTo->range('admin{3},user1{2},user2{2},{3}');
$bugTable->openedBy->range('admin');
$bugTable->deleted->range('0{10}');
$bugTable->gen(10);

$userQueryTable = zenData('userquery');
$userQueryTable->id->range('1-3');
$userQueryTable->account->range('admin');
$userQueryTable->module->range('bug');
$userQueryTable->title->range('Bug搜索查询{3}');
$userQueryTable->sql->range('"`product` = \"1\" AND `status` = \"active\"{1}", "`product` = \"all\" AND `assignedTo` = \"admin\"{1}", " 1 = 1{1}"');
$userQueryTable->gen(3);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品一,产品二,产品三');
$productTable->status->range('normal{3}');
$productTable->deleted->range('0{3}');
$productTable->gen(3);

$executionTable = zenData('project');
$executionTable->id->range('1-5');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->type->range('sprint{5}');
$executionTable->status->range('doing{3},wait{2}');
$executionTable->deleted->range('0{5}');
$executionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->getImportBugsTest(1, array(1, 2), 'all', 0, new stdclass())) && p() && e('0'); // 步骤1：非搜索模式正常情况
r($executionTest->getImportBugsTest(1, array(1), 'bysearch', 1, new stdclass())) && p() && e('0'); // 步骤2：搜索模式使用queryID
r($executionTest->getImportBugsTest(1, array(1, 2), 'bysearch', 0, new stdclass())) && p() && e('0'); // 步骤3：搜索模式使用session查询
r($executionTest->getImportBugsTest(1, array(), 'all', 0, new stdclass())) && p() && e('0'); // 步骤4：空产品列表
r($executionTest->getImportBugsTest(0, array(1, 2), 'all', 0, new stdclass())) && p() && e('0'); // 步骤5：无效executionID边界测试